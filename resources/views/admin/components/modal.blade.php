<div class="modal fade" id="{!! $id !!}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog {!! !empty($centered)?'modal-dialog-centered':null !!}" role="document">
        <div class="modal-content position-relative">
            @if(!empty($form))
                <form action="@safe($form['action'], url()->current())"
                      {!! exists('id="', $form['id'], '"') !!} method="@safe($form['method'], 'post')"
                      @if(!empty($form['multipart']))enctype="multipart/form-data"@endif>
                    @endif
                    <div class="modal-header">
                        <h5 class="modal-title">@safe($title)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">{!! $slot !!}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn @safe($cancelBtnClass, 'btn-secondary')" data-dismiss="modal">
                            @safe($closeBtn, 'Закрыть')
                        </button>
                        <button type="{!! (!empty($form) && empty($form['no-submit']))?'submit':'button' !!}"
                                class="btn @safe($saveBtnClass, 'btn-success')">@safe($saveBtn, 'Сохранить')
                        </button>
                    </div>
                    @if(!empty($form))
                </form>
            @endif
            @if (!empty($loader))
                <div class="loader modal-loader"></div>
            @endif
        </div>
    </div>
</div>
