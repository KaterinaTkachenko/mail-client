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
            $folder = 'INBOX';  
            extract(get_object_vars($this));   
            return view('index', compact('imap_conn', 'login', 'server', 'inbox', 'folders', 'folder'));
        }        
    }    

    public function changeFolder(Request $request)
    {
        Session::forget('success');        

        if($request->folder == 'sent'){
            $folder = '[Gmail]/Отправленные';
            $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 
            $inbox = imap_search($imap_conn, 'UNFLAGGED');
        }
            
        if($request->folder == 'inbox'){
            $folder = 'INBOX';
            $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 
            $inbox = imap_search($imap_conn, 'UNFLAGGED');
        }
            
        if($request->folder == 'all'){
            $folder = '[Gmail]/Вся почта';
            $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 
            $inbox = imap_search($imap_conn, 'All');
        }
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'folder'));      
    }

    public function deleteMail(Request $request)
    {
        if($request->activeFolder == 'sent'){
            $folder = '[Gmail]/Отправленные';           
        }            
        if($request->activeFolder == 'inbox'){
            $folder = 'INBOX';            
        }
        if($request->activeFolder == 'all')
            $folder = '[Gmail]/Вся почта';        

        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass)
            or die("Не удалось подключиться: " . imap_last_error());
        foreach($request->idList as $id){
            imap_delete($imap_conn, $id, FT_UID);
            imap_expunge($imap_conn);
        }                
        $inbox = imap_search($imap_conn, 'UNFLAGGED');        

        if(count($request->idList)>1)
            Session::put('success', 'Письма удалены.');
        else
            Session::put('success', 'Письмо удалено.');
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'folder'));
    }

    public function showMail(Request $request){
        if($request->activeFolder == 'sent'){
            $folder = '[Gmail]/Отправленные';           
        }            
        if($request->activeFolder == 'inbox'){
            $folder = 'INBOX';            
        }
        if($request->activeFolder == 'all')
            $folder = '[Gmail]/Вся почта';

        $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass)
            or die("Не удалось подключиться: " . imap_last_error());
        
        $inbox = imap_fetch_overview($imap_conn, $request->msgno);
        
        $structure = imap_fetchstructure($imap_conn, $request->msgno);
        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
            $message = imap_fetchbody($imap_conn,$request->msgno,2);

            if($part->encoding == 3) {
                $message = imap_base64($message);
            } else if($part->encoding == 1) {
                $message = imap_8bit($message);
            } else {
                $message = imap_qprint($message);
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
        if($request->activeFolder == 'sent'){
            $folder = '[Gmail]/Отправленные';
            $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error());            
            if($request->flag){
                imap_setflag_full($imap_conn, $request->msgno, "\\FLAGGED", ST_UID);              
            }
            else{
                imap_setflag_full($imap_conn, $request->msgno, "\\UNFLAGGED", ST_UID);
            } 
            $inbox = imap_search($imap_conn, 'UNFLAGGED');           
        }
            
        if($request->activeFolder == 'inbox'){
            $folder = 'INBOX';
            $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 
            if($request->flag){
                imap_setflag_full($imap_conn, $request->msgno, "\\FLAGGED", ST_UID);              
            }
            else{
                imap_setflag_full($imap_conn, $request->msgno, "\\UNFLAGGED", ST_UID);
            }
            $inbox = imap_search($imap_conn, 'UNFLAGGED');           
        }
            
        if($request->activeFolder == 'all'){
            $folder = '[Gmail]/Вся почта';
            $imap_conn = imap_open($this->server.imap_utf8_to_mutf7($folder), $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 
            if($request->flag){
                imap_setflag_full($imap_conn, $request->msgno, "\\FLAGGED", ST_UID);              
            }
            else{
                imap_clearflag_full($imap_conn, $request->msgno, "\\FLAGGED", ST_UID);
            }
            $inbox = imap_search($imap_conn, 'ALL');
        }        
        
        return view('mailsInFolder', compact('imap_conn', 'inbox', 'folder'));      
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
