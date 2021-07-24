<nav id="sidebar" class="sidebar show flex-row align-content-start align-items-baseline navbar-dark navbar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler"   aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse show navbar-collapse flex-column" id="navbarToggler">
    <ul>        
        @foreach($folders as $f)             
            <?php $f = imap_mutf7_to_utf8($f);?>
            @if($f == $server.'INBOX')
                <li class="active"><a data-folder="INBOX" class="js_folder">Входящие</a></li>
            @endif
            @if($f == $server.'[Gmail]/Отправленные')
                <li><a data-folder="[Gmail]/Отправленные" class="js_folder">Отправленные</a></li>
            @endif  
            @if($f == $server.'[Gmail]/Вся почта')
                <li><a data-folder="[Gmail]/Вся почта" class="js_folder">Вся почта</a></li>
            @endif                  
        @endforeach
    </ul>
    </div>
</nav>