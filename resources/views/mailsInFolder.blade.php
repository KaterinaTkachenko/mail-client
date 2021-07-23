<div class="mailsInFolder">
    @include('layouts.messages')
    @if (! empty($inbox))
        <div>
            <label class="searchL"><input type="text" name="search" class="search"></label>
        </div>
        <table class="table table-striped">            
            @foreach($inbox as $email)
                <?php
                    $overview = imap_fetch_overview($imap_conn, $email, 0); 
                    //dd($overview);
                    $message = imap_fetchbody($imap_conn, $email, "1.1");                                   
                    $date = date("d F, Y", strtotime($overview[0]->date));
                ?>
                <tr>
                    <td>
                        <input style="width: 40px; height: 20px;" class="checkit" name="checkit" type="checkbox" data-id={{$overview[0]->uid}}>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        <?php 
                            if ($folder == 'INBOX') echo $overview[0]->from;
                            else echo 'Кому: '.$overview[0]->to; 
                        ?>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}">
                        <?php if($overview[0]->subject) echo $overview[0]->subject; ?> - <?php echo $message; ?>
                    </td>
                    <td class="openMail" data-msgno="{{$overview[0]->msgno}}"><?php echo $date; ?></td>
                </tr>
            @endforeach
        </table>
    @endif
</div>