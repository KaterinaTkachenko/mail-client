<nav id="sidebar">
    <div class="sidebar-header">
        <h3></h3>
    </div>
    <ul>
        @foreach($folders as $f)
            @if($f == $server.'INBOX')
                <li><a data-folder="inbox" class="js_folder">Входящие</a></li>
            @endif
            @if($f == $server.'[Gmail]/&BB4EQgQ,BEAEMAQyBDsENQQ9BD0ESwQ1-')
                <li><a data-folder="sent" class="js_folder">Отправленныне</a></li>
            @endif                    
        @endforeach
    </ul>
</nav>