<div class="modal fade" id="modalSendMail" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-label">Форма отправки письма</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('sendmail')}}" method="POST" class="text-center needs-validation">
                    @csrf
                    <div class="form-group">
                        <label for="email">Получатель</label>
                        <input class="form-control" id="email" name="email" type="email" autofocus >
                        <div class="invalid-feedback email">
                            Обязательное поле
                        </div>                        
                    </div>
                        
                    <div class="form-group">
                        <label for="subject">Тема письма</label>
                        <input class="form-control" name="subject" type="text">	
                    </div>
                    <div class="form-group">
                        <label for="mBody">Текст письма</label>
                        <textarea class="form-control" rows="5" name="mBody" cols="50"></textarea>
                    </div>
                    <input type="hidden" name="activeFolder" value="inbox">
                    <button class="btn btn-dark">Отправить</button>	
                </form>
            </div>
        </div>
    </div>
</div>