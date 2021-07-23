<nav id="sidebar">
    <div class="sidebar-header">
        <h3></h3>
    </div>
    <ul>
        @foreach($folders as $f)
            <?php $f = imap_mutf7_to_utf8($f);?>
            @if($f == $server.'INBOX')
                <li class="active"><a data-folder="inbox" class="js_folder">Входящие</a></li>
            @endif
            @if($f == $server.'[Gmail]/Отправленные')
                <li><a data-folder="sent" class="js_folder">Отправленные</a></li>
            @endif  
            @if($f == $server.'[Gmail]/Вся почта')
                <li><a data-folder="all" class="js_folder">Вся почта</a></li>
            @endif                  
        @endforeach
    </ul>
</nav>