<?php
// autoload.php を読み込んだら、vendor下のクラスをインスタンス化できるようにする
require_once 'vendor/autoload.php';
// json形式でpostされた値を取得して配列化する
$r_post = json_decode(file_get_contents('php://input'), true);
$webPush = new \Minishlink\WebPush\WebPush([
    'VAPID' => [
        'subject' => 'http://localhost/',
        // ↓生成した公開鍵文字列を入れる
        'publicKey' => 'BA89XpnaBVXLveI886L6CODY6kdJqwIu-xdnxThTpPm9IFmjIqtdJ1wK6h4PM8xZmQjtCsTIw3dAaQ-2gNEQxjs',
        // ↓生成した秘密鍵文字列を入れる
        'privateKey' => '-YobposuwUonHsgj4-Mh_X4DrHgd9atTS8NbfpCKKLo',
        // ↑２つのようにもちろんこういったハードコーディングはよくないが、そこらへんはよしなに。。。
    ]
]);
// push通知認証用のデータ
$subscription = \Minishlink\WebPush\Subscription::create([
    // ↓検証ツール > console に表示された endpoint URL を入力
    'endpoint' => 'https://〇〇〇〇〇〇〇〇',
    // ↓検証ツール > console に表示された push_public_key を入力
    'publicKey' => '△△△△△△△△△△△△',
    // ↓検証ツール > console に表示された push_auth_token を入力
    'authToken' => 'xxxxxxxxxxxxx',
]);
// pushサーバーに通知リクエストをjson形式で送る
$report = $webPush->sendOneNotification(
    $subscription,
    json_encode([
        'title' => 'title',
        'body' => 'body',
        'url' => 'http://localhost/',
    ])
);
$r_response = [
    'status' => 200,
    'body' => '送信' . (($report->isSuccess()) ? '成功' : '失敗')
];
echo json_encode($r_response);
