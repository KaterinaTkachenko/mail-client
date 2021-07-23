@extends('layouts.main-layout')

@section('main')
<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3></h3>
        </div>

        <ul>
            <li></li>
        </ul>
    </nav>
    <!-- Page Content -->
    <div id="content">
        <a href="" class="mainBtn" data-toggle="modal" data-target="#modalSendMail">Написать письмо</a> 
        <br>

        <div>
            <?php 
                /* IMAP Connection code with GMAIL IMAP */
                $imap_conn = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', 'test.tkachenko@gmail.com', 'TesT1234567TesT') or die('Cannot connect to Gmail: ' . imap_last_error());
                
                /* SET email subject filter criteria */
                $inbox = imap_search($imap_conn, 'ALL');
                
                

                ////////////////////////////////////////////////////////////
                $imap = imap_open('{imap.gmail.com:993/imap/ssl}', "test.tkachenko@gmail.com", "TesT1234567TesT");
                $folders = imap_list($imap, "{imap.gmail.com:993/imap/ssl}", "*");
                 echo "<ul>"; 
                    foreach ($folders as $folder) { 
                        
                        $folder = str_replace("{imap.gmail.com:993/imap/ssl}", "", mb_convert_encoding($folder, "UTF-8", "UTF7-IMAP")); 
                        echo '<li><a href="mail.php?folder=' . $folder . '&func=view">' . $folder . '</a></li>'; } 
                echo "</ul>"; 



                if (! empty($inbox)) {
            ?>
            <table class="table table-striped">
                <?php
                foreach ($inbox as $email) {
                    // Get email header information
                    $overview = imap_fetch_overview($imap_conn, $email, 0);
                    // Get email body
                     $message = imap_fetchbody($imap_conn, $email, "1.1");                                     
                    $date = date("d F, Y", strtotime($overview[0]->date));
                ?>
            <tr>
                <td>
                <?php echo $overview[0]->from; ?>
                </td>
                <td>
                <?php if($overview[0]->subject) echo $overview[0]->subject; ?> - <?php echo $message; ?>
                </td>
                <td>
                <?php echo $date; ?>
                </td>
            </tr>
                <?php
                } 
                ?>
            </table>
        <?php
            } 
            // Close imap connection
            imap_close($imap_conn);
            
        ?>
        </div>

        @if(session('success'))
            <div class="alert alert-secondary">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                {{session('success')}}
            </div>			
        @endif  
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif  
    </div>
@endsection