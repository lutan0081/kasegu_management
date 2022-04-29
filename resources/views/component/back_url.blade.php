<!-- URL発行 -->
<div class="modal fade" id="urlModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- ヘッダー -->
            <div class="modal-header">
                <div class="modal-title info_title" id="exampleModalLabel">
                    <i class="fas fa-paper-plane icon_blue me-2"></i>申込URL発行
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- ボディ -->
            <div class="modal-body">
                <form id="modalForm" class="needs-validation" novalidate>

                    <!-- 説明 -->
                    <div class="my-3">
                        入力したメールアドレスに申込URLが届きます。<br>
                        <span class="text_red">メールが届かない場合、最初からやり直してください。</span> 
                    </div>  
                    <!-- 説明 -->

                    <!-- 入力項目 -->
                    <div class="mb-3">
                        <label class="col-form-label">名前</label>
                        <input type="text" class="form-control" id="application_name" required>
                        <div class="invalid-feedback" id ="application_name_error">
                            名前は、必須です。
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">E-mail</label>
                        <input type="email" class="form-control" id="application_mail" required>
                        <div class="invalid-feedback" id ="application_mail_error">
                            E-mailは、必須です。
                        </div>
                    </div>
                    <!-- 入力項目 -->

                </form>
            </div>
            <!-- ボディ -->

            <!-- フッター -->
            <div class="modal-footer">

                <div class="col my-3">
                    <!-- 戻る -->
                    <button type="button" id="btn_modal_url_back" class="btn btn-outline-primary btn-default" data-bs-dismiss="modal">戻る</button>

                    <!-- 送信 -->
                    <button type="button" id="btn_modal_url_send" class="btn btn-outline-primary btn-default float-end">送信</button>
                </div>

            </div>
            <!-- フッター -->
            
        </div>
    </div>
</div>
<!-- URL発行 -->