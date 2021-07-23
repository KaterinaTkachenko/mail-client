@extends('layouts.main-layout')
@section('main')
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>

    <div class="content">
        <div class="content__header mb-4">
            <a href="" class="mainBtn deleteItems">Удалить выбранные</a>
            <a href="" class="mainBtn sendMailBtn" data-toggle="modal" data-target="#modalSendMail">Написать письмо</a>
        </div>

        <div class="content__main">
            @if (! empty($inbox))
                @include('mailsInFolder')
            @endif
            <?php imap_close($imap_conn);?>
        </div>
    </div>
@endsection