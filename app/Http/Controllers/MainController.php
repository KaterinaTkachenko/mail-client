<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class MainController extends Controller
{
    public $login;
    public $pass;
    public $server;
    
    public function __construct()
    {
        $this->login = \Config::get('myConfig.login');
        $this->pass = \Config::get('myConfig.pass');
        $this->server = '{imap.gmail.com:993/imap/ssl}';
    }
    public function index()
    {        
        if (! function_exists('imap_open')) {
            echo "Error: IMAP is not configured.";
            exit();
        }
        else{
            //Connect to Inbox Gmail test mailbox
            $imap_conn = imap_open($this->server.'INBOX', $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error());
                
            // SET filter criteria
            $inbox = imap_search($imap_conn, 'UNFLAGGED');

            //get Gmail folders
            $folders = imap_list($imap_conn, "{imap.gmail.com:993/imap/ssl}", "*");
            $activeFolder = 'INBOX';  
            extract(get_object_vars($this));
            return view('index', compact('imap_conn', 'login', 'server', 'inbox', 'folders', 'activeFolder'));
        }        
    }    

    public function changeFolder(Request $request)
    {
        Session::forget('success');
        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($request->activeFolder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 
        $request->activeFolder == '[Gmail]/Вся почта' ?
            $inbox = imap_search($imap_conn, 'ALL') :
            $inbox = imap_search($imap_conn, 'UNFLAGGED');
        $activeFolder = $request->activeFolder;           
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'activeFolder'));      
    }

    public function deleteMail(Request $request)
    {
        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($request->activeFolder), $this->login, $this->pass) or die("Не удалось подключиться: " . imap_last_error());
        foreach($request->idList as $id){
            imap_delete($imap_conn, $id, FT_UID);
            imap_expunge($imap_conn);        
        } 
                     
        $request->activeFolder == '[Gmail]/Вся почта' ?
            $inbox = imap_search($imap_conn, 'ALL') :
            $inbox = imap_search($imap_conn, 'UNFLAGGED');       
        
        $activeFolder = $request->activeFolder; 
        count($request->idList)>1 ?
            Session::put('success', 'Письма удалены.') :
            Session::put('success', 'Письмо удалено.');
        
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'activeFolder'));
    }

    public function showMail(Request $request){
        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($request->activeFolder), $this->login, $this->pass) or die("Не удалось подключиться: " . imap_last_error());
        $inbox = imap_fetch_overview($imap_conn, $request->msgno);
        
        $structure = imap_fetchstructure($imap_conn, $request->msgno);
        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
            $message = imap_fetchbody($imap_conn,$request->msgno,2);

            switch ($part->encoding) {
                case 3:
                    $message = imap_base64($message);
                    break;
                case 1:
                    $message = imap_8bit($message);
                    break;
                default:
                    $message = imap_qprint($message);
                    break;
            }          
        }
        else{
            $message = imap_qprint(imap_body($imap_conn, $request->msgno, 2));
        }
        return view('mailBody', compact('message', 'inbox'));
    }

    public function moveToArchive(Request $request)
    {
        Session::forget('success');
        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($request->activeFolder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error());        
        $request->flag ?
            imap_setflag_full($imap_conn, $request->msgno, "\\FLAGGED", ST_UID) :
            imap_clearflag_full($imap_conn, $request->msgno, "\\FLAGGED", ST_UID);
        $request->activeFolder == '[Gmail]/Вся почта' ?
            $inbox = imap_search($imap_conn, 'ALL') :
            $inbox = imap_search($imap_conn, 'UNFLAGGED');
        $activeFolder = $request->activeFolder; 
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'activeFolder'));      
    }

    public function search(Request $request){
        Session::forget('success');
        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($request->activeFolder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error());
        
        $str = '';
        if($request->creteria == 'to')
            $str = "TO \"$request->searchStr\"";
        if($request->creteria == 'from')
            $str = "FROM \"$request->searchStr\"";

        $request->activeFolder == '[Gmail]/Вся почта' ?
            $inbox = imap_search($imap_conn, 'ALL '.$str) :
            $inbox = imap_search($imap_conn, 'UNFLAGGED '.$str);
        if(!$inbox){
            $request->activeFolder == '[Gmail]/Вся почта' ?
            $inbox = imap_search($imap_conn, 'ALL') :
            $inbox = imap_search($imap_conn, 'UNFLAGGED');
            Session::put('success', 'Письма по заданному критерию не найдены');
        }
        $activeFolder = $request->activeFolder;
        $searchStr =  $request->searchStr;     
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'activeFolder', 'searchStr'));
    }

    public function sendmail(Request $request)
    {
        if($request->email){
            $data = array(
                "recepient"  =>  $request->email, 
                "subject" =>  $request->subject,
                "body"   => $request->mBody
            );
            event(new \App\Events\SendMailEvent($data)); 
            return redirect('/')->with('success', 'Письмо отправлено.');
        }
        else{            
            return redirect('/')->withErrors('email', 'Введите, пожалуйста, email получателя');
        } 
    }
}
