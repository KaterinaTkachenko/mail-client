<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            $inbox = imap_search($imap_conn, 'ALL');

            //get Gmail folders
            $folders = imap_list($imap_conn, "{imap.gmail.com:993/imap/ssl}", "*"); 
            $folder = 'INBOX';  
            extract(get_object_vars($this));   
            return view('index', compact('imap_conn', 'login', 'server', 'inbox', 'folders', 'folder'));
        }        
    }    

    public function changeFolder(Request $request)
    {        
        //Connect to Folder        
        if($request->folder == 'sent')
            $folder = '[Gmail]/&BB4EQgQ,BEAEMAQyBDsENQQ9BD0ESwQ1-';
        if($request->folder == 'inbox')
            $folder = 'INBOX';
        $imap_conn = imap_open($this->server.$folder, $this->login, $this->pass) or die('Cannot connect to Gmail: ' . imap_last_error()); 

        // SET filter criteria
        $inbox = imap_search($imap_conn, 'ALL');

        return view('mailsInFolder', compact('imap_conn', 'inbox', 'folder'));      
    }

    public function deleteMail(Request $request)
    {
        $imap_conn = imap_open($this->server.'INBOX', $this->login, $this->pass)
            or die("Не удалось подключиться: " . imap_last_error());

        imap_delete($imap_conn, $request->id, FT_UID);
        imap_expunge($imap_conn);
        
        // SET filter criteria
        $inbox = imap_search($imap_conn, 'ALL');
        $folder = 'INBOX';
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
            return redirect('/')->with('success', 'Письмо отправлено успешно');
        }
        else{            
            return redirect('/')->withErrors('email', 'Введите, пожалуйста, email получателя');
        } 
    }
}
