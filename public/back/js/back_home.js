/**
 * ページネーションの要素センター
 */
$(function() {
	$(".pagination").addClass("justify-content-center");
	$("#links").show();
});

/**
 * カウントアップ
 */
// $(function(){
// 	var countElm = $('.count'),
// 	countSpeed = 10;

// 	countElm.each(function(){
// 		var self = $(this),
// 		countMax = self.attr('data-num'),
// 		thisCount = self.text(),
// 		countTimer;

// 		function timer(){
// 		countTimer = setInterval(function(){
// 			var countNext = thisCount++;
// 			self.text(countNext);

// 			if(countNext == countMax){
// 			clearInterval(countTimer);
// 			}
// 		},countSpeed);
// 		}
// 		timer();
// 	});
// });

/**
 * 削除ボタンの処理(個別)
 */
$(function() {
	$("#btn_delete").on('click', function(e) {
		console.log("btn_deleteボタンがクリックされています.");

		e.preventDefault();

		/**
		 * ラジオボタンに値がない場合 = 0
		 * ラジオボタンに値がある場合 = 1
		 */
		if ($('input[name=flexRadioDisabled]:checked').length <= 0) {
			return false;
		}

		/**
		 * id取得
		 */
		var update_id = $('input[name=flexRadioDisabled]:checked').attr('id').split('_')[2];
		console.log(update_id);

		/**
		 * アラートの表示
		 */
		var options = {
			title: "削除しますか？",
			icon: "warning",
			buttons: {
			cancel: "Cancel",
			ok: true
			}
		};

		// okの場合の処理
		swal(options)

		// then() メソッドを使えばクリックした時の値が取れます
		.then(function(val) {

			// NGボタンが押された時の処理
			if (val == null) {

				console.log("NG");
				return false;

			// OKボタンが押された時の処理
			} else {
			console.log("OKボタンをクリックしました");
		
			// 送信用データ設定
			let sendData = {
				"update_id": update_id,
			};

			console.log(sendData);

			$.ajaxSetup({
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
			});

			$.ajax({
				type: 'post',
				url: 'adminInfoDeleteEntry',
				dataType: 'json',
				data: sendData,
			
			// 接続処理
			}).done(function(data) {

			console.log("ajax通信後のstatus:" + data.status);

			// controller側でtrueの処理
			if(data.status == true){
				
			// alertの設定
			var options = {
				title: "削除が完了しました。",
				icon: "success",
				buttons: {
				ok: true
				}
			};

			// then() OKを押した時の処理
			swal(options)
				.then(function(val) {
				// Okボタン処理
				if (val) {
				
				// リロード
				location.reload();
				}
			});
			};
			
			// ajax接続失敗の時の処理
			}).fail(function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
			});
		}//swalのthen
		});
	});
});

/**
 * 新着情報(新規登録)
 */
$(function() {
	$("#btn_edit").on('click', function(e) {
		console.log("新規登録ボタンがクリックされています.");

		// 記載必須（別URLに誘導される）
		e.preventDefault();

		/**
		 * formに空白有の場合、赤文字で表示
		 */
		let forms = $('.needs-validation');
		console.log(forms[0].length);

		//  validationの初期値
		let v_check = true;
		for (let i = 0; i < forms[0].length; i++) {
			
			// forms[0]=form.[i]=中の項目;
			let form = forms[0][i];
			console.log('from:'+ form);

			// タグ名を取得 input or button
			let tag = $(form).prop("tagName");
			console.log('tag:'+ tag);

			let f_id = $(form).prop("id");
			console.log('id:'+ f_id);

			// form内のbuttonはスルー
			if (tag == 'BUTTON') {
				continue;
			}

			// formの値を確認->クラス付与（Message表示）
			let val = $(form).val();
			console.log(val);
			if (val === '') {
				$(forms).addClass("was-validated");
				v_check = false;
			}
		} 

		/**
		 * チェック=falseの処理
		 */
		console.log(v_check);
		if (v_check === false) {
			return false;
		}

		/**
		 * inputタグからの値取得
		 */
		// タイトル
		let title_name = $("#title_name").val();
		// 内容
		let contents_name = $("#contents_name").val();

		// 一度登録ボタンを押した後、ボタンを押せなくする
		$('#btn_delete').prop('disabled',true);
		$('#btn_back').prop('disabled',true);
		$('#btn_edit').prop('disabled',true);
		/**
		 * ajax
		 */
		// 送信用データ設定
		let sendData = {
				"title_name": title_name,
				"contents_name": contents_name,
		};

		console.log("send_data:" + sendData);

		$.ajaxSetup({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
		});
		$.ajax({
			type: 'post',
			url: 'adminInfoEditEntry',
			dataType: 'json',
			data: sendData,
		
		// 接続処理
		}).done(function(data) {
			console.log("status:" + data.status);
			console.log("ajax通信後の処理");
			console.log(data);

			// ajax送信前に付与された.was-validatedクラスを削除、テキストの値を空にする
			$('#updateForm').removeClass('was-validated');
			$('.invalid-feedback').text('');

			/**
			 * formの全要素をerror_Messageを非表示に変更
			 * form数をループ処理
			 */
			let forms = $('.needs-validation');
			for (let i = 0; i < forms[0].length; i++) {
				let form = forms[0][i];
				$(form).addClass("is-valid");
				$(form).removeClass('is-invalid');
			}

			/**
			 * falseの処理
			 */
			if(data.status == false){
				console.log("errorkeys:" + data.errkeys);

				// falseの時、再度編集する為、disabledをfalseに切り替える
				$('#btn_delete').prop('disabled',false);
				$('#btn_back').prop('disabled',false);
				$('#btn_edit').prop('disabled',false);

				/**
				 * formの全要素をerror_Messageを表示に変更
				 * error数だけループ処理
				 */
				for (let i = 0; i < data.errkeys.length; i++) {
					//　bladeの各divにclass指定
					let id_key = "#" + data.errkeys[i];
					$(id_key).addClass('is-invalid');

					// 表示箇所のMessageのkey取得
					let msg_key = "#" + data.errkeys[i] + "_error"
					// error_messageテキスト追加
					$(msg_key).text(data.messages[i]);
					$(msg_key).show();
				}
				return false;
			}

			// Errorがない場合=true->画面upload
			if(data.status == true){

				console.log("status:" + data.status);

				// alertの設定
				var options = {
					title: "登録が完了しました。",
					icon: "success",
					buttons: {
					ok: true
					}
				};
				// then() OKを押した時の処理
				swal(options)
					.then(function(val) {
					if (val) {
						// Okボタン処理
						location.reload();
					};
				});
				return false;
			};
		
		// ajax接続失敗の時の処理
		}).fail(function(jqXHR, textStatus, errorThrown) {
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
		});
	});

    /**
     * dashboardをクリック
     * 申込管理
     */
    $(".dashboard_box_inner_1").on('dblclick', function(e) {
        console.log('dashboard_box_inner_1_click処理');
        
        window.location.href = 'backAppInit';
    });

    /**
     * dashboardをクリック
     * 契約管理
     */
    $(".dashboard_box_inner_2").on('dblclick', function(e) {
        console.log('dashboard_box_inner_2_click処理');
        
        window.location.href = 'backContractInit';
    });

});
