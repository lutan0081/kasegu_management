<?php

// ログアウト
Route::get('logOut', 'Common\LogOutController@logOut');

/**
 * front(一般)
 */
// ログイン画面(表示)
Route::get('loginInit', 'Login\LoginController@loginInit');

// ログイン画面(バリデーション)
Route::post('loginApi', 'Login\LoginController@loginApi');

// 表示
Route::get('/', 'Front\Home\FrontHomeController@frontHomeInit');

// お知らせ
Route::get('frontInfoInit', 'Front\Info\FrontInfoController@frontInfoInit');

// ユーザ登録
Route::get('frontUserInit', 'Front\User\FrontUserController@frontUserInit');

// 登録
Route::get('frontUserEdit', 'Front\User\FrontUserController@frontUserEdit');

// 認証メール送信
Route::get('frontUserMail', 'Front\User\FrontUserController@frontUserMail');

// 仮登録画面(表示)
Route::get('frontUserComplete', 'Front\User\FrontUserController@frontUserComplete');

// サイト利用規約
Route::get('frontSiteUseInit', 'Front\SiteUse\FrontSiteUseController@frontSiteUseInit');

// 個人情報保護
Route::get('frontPrivacyInit', 'Front\Privacy\FrontPrivacyController@frontPrivacyInit');

// お問合せ(表示)
Route::get('frontContactInit', 'Front\Contact\FrontContactController@frontContactInit');

// お問合わせ(登録)
Route::get('frontContactEntry', 'Front\Contact\FrontContactController@frontContactEntry');

// お問合わせ(管理者にメール送信)
Route::get('frontContactMailEntry', 'Front\Contact\FrontContactController@frontContactMailEntry');

// 申込Url発行
Route::post('frontAppUrlEntry', 'Front\App\FrontAppController@frontAppUrlEntry');

// 新規表示(フロント側表示)
Route::get('frontAppNewInit', 'Front\App\FrontAppController@frontAppNewInit');

// 登録(編集登録に分岐)
Route::post('frontAppEditEntry', 'Front\App\FrontAppController@frontAppEditEntry');

// 編集表示(フロント側表示)
Route::get('frontAppEditInit', 'Front\App\FrontAppController@frontAppEditInit');

// 同居人(ダブルクリックの表示)
Route::post('frontAppHouseMateInit', 'Front\App\FrontAppController@frontAppHouseMateInit');

// 同居人(削除)
Route::post('frontAppHouseMateDeleteEntry', 'Front\App\FrontAppController@frontAppHouseMateDeleteEntry');

// 画像(削除:詳細)
Route::post('frontDeleteEntryImgDetail', 'Front\App\FrontAppController@frontDeleteEntryImgDetail');

/**
 * back
 */
// home(表示)
Route::get('backHomeInit', 'Back\Home\BackHomeController@backHomeInit')->middleware("kasegu_auth");

// 申込管理(表示)
Route::any('backAppInit', 'Back\App\BackAppController@backAppInit')->middleware("kasegu_auth");

// 申込管理(新規)
Route::get('backAppNewInit', 'Back\App\BackAppController@backAppNewInit')->middleware("kasegu_auth");

// 申込管理(新規・編集に分岐)
Route::post('backAppEditEntry', 'Back\App\BackAppController@backAppEditEntry')->middleware("kasegu_auth");

// 申込管理(編集表示)
Route::get('backAppEditInit', 'Back\App\BackAppController@backAppEditInit')->middleware("kasegu_auth");

// 申込管理(削除)
Route::post('backAppDeleteEntry', 'Back\App\BackAppController@backAppDeleteEntry')->middleware("kasegu_auth");

// 申込管理(同居人ダブルクリックの表示)
Route::post('backAppHouseMateInit', 'Back\App\BackAppController@backAppHouseMateInit')->middleware("kasegu_auth");

// 申込管理(申込管理削除)
Route::post('backAppHouseMateDeleteEntry', 'Back\App\BackAppController@backAppHouseMateDeleteEntry')->middleware("kasegu_auth");

// 申込管理(申込URL再発行:モーダル開いたときにURL生成)
Route::post('backAppModalInit', 'Back\App\BackAppController@backAppModalInit')->middleware("kasegu_auth");

// 申込管理(申込URL再発行)
Route::post('backAppMailEntry', 'Back\App\BackAppController@backAppMailEntry')->middleware("kasegu_auth");

// 画像(削除:詳細)
Route::post('backDeleteEntryImgDetail', 'Back\App\BackAppController@backDeleteEntryImgDetail')->middleware("kasegu_auth");

// 契約に進む
Route::post('backAppNextStageEntry', 'Back\App\BackAppController@backAppNextStageEntry')->middleware("kasegu_auth");

// 法務局(表示・検索)
Route::any('backLegalPlaceInit', 'Back\LegalPlace\BackLegalPlaceController@backLegalPlaceInit')->middleware("kasegu_auth");

// 法務局(新規)
Route::get('backLegalPlaceNewInit', 'Back\LegalPlace\BackLegalPlaceController@backLegalPlaceNewInit')->middleware("kasegu_auth");

// 法務局(編集)
Route::get('backLegalPlaceEditInit', 'Back\LegalPlace\BackLegalPlaceController@backLegalPlaceEditInit')->middleware("kasegu_auth");

// 法務局(登録:新規/編集に分岐)
Route::post('backLegalPlaceEditEntry', 'Back\LegalPlace\BackLegalPlaceController@backLegalPlaceEditEntry')->middleware("kasegu_auth");

// 法務局(削除:新規/編集に分岐)
Route::post('backLegalPlaceDeleteEntry', 'Back\LegalPlace\BackLegalPlaceController@backLegalPlaceDeleteEntry')->middleware("kasegu_auth");

// 宅地建物取引士(表示)
Route::any('backUserLicenseInit', 'Back\UserLicense\BackUserLicenseController@backUserLicenseInit')->middleware("kasegu_auth");

// 宅地建物取引士(新規表示)
Route::get('backUserLicenseNewInit', 'Back\UserLicense\BackUserLicenseController@backUserLicenseNewInit')->middleware("kasegu_auth");

// 宅地建物取引士(編集)
Route::get('backUserLicenseEditInit', 'Back\UserLicense\BackUserLicenseController@backUserLicenseEditInit')->middleware("kasegu_auth");

// 宅地建物取引士(新規・編集登録に分岐)
Route::post('backUserLicenseEditEntry', 'Back\UserLicense\BackUserLicenseController@backUserLicenseEditEntry')->middleware("kasegu_auth");

// 宅地建物取引士(削除)
Route::post('backUserLicenseDeleteEntry', 'Back\UserLicense\BackUserLicenseController@backUserLicenseDeleteEntry')->middleware("kasegu_auth");

// 保証協会(表示)
Route::any('backGuarantyAssociationInit', 'Back\BackGuarantyAssociation\BackGuarantyAssociationController@backGuarantyAssociationInit')->middleware("kasegu_auth");

// 保証協会(新規表示)
Route::get('backGuarantyAssociationNewInit', 'Back\BackGuarantyAssociation\BackGuarantyAssociationController@backGuarantyAssociationNewInit')->middleware("kasegu_auth");

// 保証協会(編集表示)
Route::get('backGuarantyAssociationEditInit', 'Back\BackGuarantyAssociation\BackGuarantyAssociationController@backGuarantyAssociationEditInit')->middleware("kasegu_auth");

// 保証協会(登録:新規/編集に分岐)
Route::post('backGuarantyAssociationEditEntry', 'Back\BackGuarantyAssociation\BackGuarantyAssociationController@backGuarantyAssociationEditEntry')->middleware("kasegu_auth");

// 保証協会(削除)
Route::post('backGuarantyAssociationDeleteEntry', 'Back\BackGuarantyAssociation\BackGuarantyAssociationController@backGuarantyAssociationDeleteEntry')->middleware("kasegu_auth");

// アカウント情報(表示)
Route::get('backUserInit', 'Back\User\BackUserController@backUserInit')->middleware("kasegu_auth");

// アカウント情報(専任取引士)
Route::post('backUserLicenseChange', 'Back\User\BackUserController@backUserLicenseChange')->middleware("kasegu_auth");

// アカウント情報(法務局)
Route::post('backLegalPlaceChange', 'Back\User\BackUserController@backLegalPlaceChange')->middleware("kasegu_auth");

// アカウント情報(不動産保証協会)
Route::post('backGuarantyAssociationChange', 'Back\User\BackUserController@backGuarantyAssociationChange')->middleware("kasegu_auth");

// アカウント情報(登録・編集)
Route::post('backUserEditEntry', 'Back\User\BackUserController@backUserEditEntry')->middleware("kasegu_auth");

// 特約事項一覧(表示)
Route::any('backSpecialContractInit', 'Back\SpecialContract\BackSpecialContractController@backSpecialContractInit')->middleware("kasegu_auth");

// 特約事項一覧(編集)
Route::post('backSpecialContractEdit', 'Back\SpecialContract\BackSpecialContractController@backSpecialContractEdit')->middleware("kasegu_auth");

// 特約事項一覧(登録:新規/編集に分岐)
Route::post('backSpecialContractEntry', 'Back\SpecialContract\BackSpecialContractController@backSpecialContractEntry')->middleware("kasegu_auth");

// 特約事項一覧(削除)
Route::post('backSpecialDeleteEntry', 'Back\SpecialContract\BackSpecialContractController@backSpecialDeleteEntry')->middleware("kasegu_auth");

// 特約事項一覧(並び替え)
Route::post('backSpecialSortEntry', 'Back\SpecialContract\BackSpecialContractController@backSpecialSortEntry')->middleware("kasegu_auth");

// 集金口座一覧(表示)
Route::any('backBankInit', 'Back\Bank\BackBankController@backBankInit')->middleware("kasegu_auth");

// 集金口座詳細(表示)
Route::get('backBankNewInit', 'Back\Bank\BackBankController@backBankNewInit')->middleware("kasegu_auth");

// 集金口座詳細(編集)
Route::get('backBankEditInit', 'Back\Bank\BackBankController@backBankEditInit')->middleware("kasegu_auth");

// 集金口座詳細(新規・登録分岐)
Route::post('backBankEditEntry', 'Back\Bank\BackBankController@backBankEditEntry')->middleware("kasegu_auth");

// 集金口座削除
Route::post('backBankDeleteEntry', 'Back\Bank\BackBankController@backBankDeleteEntry')->middleware("kasegu_auth");

// 契約一覧(表示)
Route::any('backContractInit', 'Back\Contract\BackContractController@backContractInit')->middleware("kasegu_auth");

// 契約詳細(新規表示)
Route::get('backContractNewInit', 'Back\Contract\BackContractController@backContractNewInit')->middleware("kasegu_auth");

// 契約詳細(編集表示)
Route::get('backContractEditInit', 'Back\Contract\BackContractController@backContractEditInit')->middleware("kasegu_auth");

// 商号コンボボックス変更(データ取得)
Route::post('backChangeCompanyLicense', 'Back\Contract\BackContractController@backChangeCompanyLicense')->middleware("kasegu_auth");

// 宅地建物取引士コンボボックス変更(データ取得)
Route::post('backChangeUserLicense', 'Back\Contract\BackContractController@backChangeUserLicense')->middleware("kasegu_auth");

// 契約詳細(登録)
Route::post('backContractEditEntry', 'Back\Contract\BackContractController@backContractEditEntry')->middleware("kasegu_auth");

// 契約詳細(削除)
Route::post('backContractDeleteEntry', 'Back\Contract\BackContractController@backContractDeleteEntry')->middleware("kasegu_auth");

// 銀行一覧取得(モーダル初期表示・検索)
Route::post('backSearchBank', 'Back\Contract\BackContractController@backSearchBank')->middleware("kasegu_auth");

// 同居人(登録)
Route::post('backContractHouseMateEditEntry', 'Back\Contract\BackContractController@backContractHouseMateEditEntry')->middleware("kasegu_auth");

// 同居人(編集表示)
Route::post('backContractHouseMateEditInit', 'Back\Contract\BackContractController@backContractHouseMateEditInit')->middleware("kasegu_auth");

// 同居人(削除)
Route::post('backContractHouseMateDeleteEntry', 'Back\Contract\BackContractController@backContractHouseMateDeleteEntry')->middleware("kasegu_auth");

// 契約書Excelダウンロード
Route::get('backContractExcelEntry', 'Common\ExcelController@backContractExcelEntry')->middleware("kasegu_auth");

// 契約書Excelダウンロード
Route::get('excelEntryDummy', 'Common\ExcelController@excelEntryDummy')->middleware("kasegu_auth");

// 申込書Excelダウンロード
Route::get('backApplicationExcelEntry', 'Common\ExcelController@backApplicationExcelEntry')->middleware("kasegu_auth");

/**
 * admin(管理者)
 */
// ホーム画面
Route::get('adminHomeInit', 'Admin\Home\AdminHomeController@adminHomeInit')->middleware("kasegu_auth");

// 新着情報
Route::any('adminInformationInit', 'Admin\Information\AdminInformationController@adminInformationInit')->middleware("kasegu_auth");

Route::post('adminInformationEditInit', 'Admin\Information\AdminInformationController@adminInformationEditInit')->middleware("kasegu_auth");

Route::post('adminInformationEditEntry', 'Admin\Information\AdminInformationController@adminInformationEditEntry')->middleware("kasegu_auth");

Route::post('adminDeleteEntry', 'Admin\Information\AdminInformationController@adminDeleteEntry')->middleware("kasegu_auth");

// アカウント
Route::any('adminUserInit', 'Admin\User\AdminUserController@adminUserInit')->middleware("kasegu_auth");

/**
 * テスト
 */
// 表示
Route::get('testInit', 'Test\TestController@testInit');

// 登録
Route::post('testImgEntry', 'Test\TestController@testImgEntry');