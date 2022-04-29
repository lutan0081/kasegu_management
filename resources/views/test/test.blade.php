<!DOCTYPE html>
<html lang="ja">
<head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>promise</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" />
    </head>
    <body>

        <div class="p-5">
            <div>
                <input type="button" id="btn_sort" value="sort確定" />
            </div>

            <table class="table" id="sort_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="sortable-tr" id="tr_1">
                        <td>1</td>
                        <td>Mark</td>
                    </tr>
                    <tr class="sortable-tr" id="tr_2">
                        <td>2</td>
                        <td>Jacob</td>
                    </tr>
                    <tr class="sortable-tr" id="tr_3">
                        <td>3</td>
                        <td>Larry the Bird</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
        <!-- ★重要 jquery-ui -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            $(function() {

                // ★重要 ソート設定
                $('#sort_table').sortable({"items": "tr.sortable-tr"});

                // 確定ボタン
                $("#btn_sort").on('click', function(e) {
                    e.preventDefault();

                    let ids = [];
                    // テーブルのtr分ループ
                    $("#sort_table tbody tr").each(function () {
                        let tr_id = $(this).attr("id");
                        // console.log(tr);
                        ids.push(tr_id.split("_")[1]);
                    });
                    // idの順番の確認
                    console.log(ids);

                    // ajax送信
                });
            });
        </script>
    </body>
</html>