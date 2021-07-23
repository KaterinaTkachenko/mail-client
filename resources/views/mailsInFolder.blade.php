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
            <td>
                <?php 
                    if ($folder == 'INBOX') echo $overview[0]->from;
                    else echo 'Кому: '.$overview[0]->to; 
                ?>
            </td>
            <td>
                <?php if($overview[0]->subject) echo $overview[0]->subject; ?> - <?php echo $message; ?>
            </td>
            <td><?php echo $date; ?></td>
        </tr>
    @endforeach
</table>