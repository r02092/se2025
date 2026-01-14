<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\TransformCoordTrait;
use DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    use TransformCoordTrait;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'login_name' => 'share_admin',
                'password' => password_hash('password', PASSWORD_ARGON2ID),
                'permission' => 0,
                'name' => 'Share',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_name' => 'yabashi_harimaaaa',
                'password' => password_hash('password', PASSWORD_ARGON2ID),
                'permission' => 1,
                'name' => 'はりまや',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_name' => 'hekiku_ramen',
                'password' => password_hash('password', PASSWORD_ARGON2ID),
                'permission' => 2,
                'name' => '中華そば 碧空',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_name' => 'tsuruhashi04',
                'password' => password_hash('password', PASSWORD_ARGON2ID),
                'permission' => 1,
                'name' => 'ツル☆ハシ',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_name' => 'yanagi_yuki',
                'password' => password_hash('password', PASSWORD_ARGON2ID),
                'permission' => 1,
                'name' => '柳 勇樹',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_name' => 'test_user',
                'password' => password_hash('password123', PASSWORD_ARGON2ID),
                'permission' => 1,
                'name' => 'テスト太郎',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // 二要素認証テスト用
        DB::table('users')->insert([
            [
                'login_name' => '2fa_user',
                'password' => password_hash('password123', PASSWORD_ARGON2ID),
                'permission' => 1,
                'name' => '二要素 次郎',
                'provider' => 0,
                'icon_ext' => 'png',
                'num_plan_std' => 0,
                'num_plan_prm' => 0,
                'totp_secret' => '12345678', //この項目があるので分ける
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('spots')->insert([
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 0,
                'name' => 'chimney',
                'lng' => $this->encodeLng(133.676274),
                'lat' => $this->encodeLat(33.583968),
                'postal_code' => 7820024,
                'addr_city' => 39212,
                'addr_detail' => '土佐山田町神通寺346-2',
                'description' =>
                    '人気絵本「パンどろぼう」のモチーフになったとされる、土佐山田のパン屋。',
                'img_ext' => 'jpg',
                'stamp_key' => $this->randKey(),
                'shows' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 6,
                'name' => 'ごめん・なはり線',
                'lng' => $this->encodeLng(133.906497),
                'lat' => $this->encodeLat(33.504458),
                'postal_code' => 7820010,
                'addr_city' => 39203,
                'addr_detail' => '東浜294',
                'description' =>
                    '南国市後免町と安芸郡奈半利町を結ぶ、2002年に開業した第三セクター路線。JR四国の路線と接続している。',
                'img_ext' => 'jpg',
                'stamp_key' => $this->randKey(),
                'shows' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 6,
                'name' => 'とさでん交通（電車）',
                'lng' => $this->encodeLng(133.54293),
                'lat' => $this->encodeLat(33.559524),
                'postal_code' => 7800833,
                'addr_city' => 39201,
                'addr_detail' => '南はりまや町1丁目',
                'description' =>
                    '南国市・高知市・吾川郡いの町を結ぶ路面電車。高知県唯一の電車でもある。',
                'img_ext' => 'jpg',
                'stamp_key' => $this->randKey(),
                'shows' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 2,
                'name' => 'はりまや橋',
                'lng' => $this->encodeLng(133.542639),
                'lat' => $this->encodeLat(33.559944),
                'postal_code' => 7800822,
                'addr_city' => 39201,
                'addr_detail' => 'はりまや町1丁目',
                'description' =>
                    '高知市の中心部にある橋。日本三大がっかり名所の一つとして知られる。',
                'img_ext' => 'jpg',
                'stamp_key' => $this->randKey(),
                'shows' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 0,
                'name' => 'ひばり食堂',
                'lng' => $this->encodeLng(133.663903),
                'lat' => $this->encodeLat(33.764472),
                'postal_code' => 7890312,
                'addr_city' => 39344,
                'addr_detail' => '高須226',
                'description' =>
                    'デカ盛りの聖地として知られる食堂。メディアに取り上げられることも多く、全国的に有名。',
                'img_ext' => 'jpg',
                'stamp_key' => $this->randKey(),
                'shows' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'plan' => 1,
                'type' => 1,
                'name' => 'ひろめ市場',
                'lng' => $this->encodeLng(133.535937),
                'lat' => $this->encodeLat(33.560116),
                'postal_code' => 7800841,
                'addr_city' => 39201,
                'addr_detail' => '帯屋町2丁目3-1',
                'description' =>
                    '飲食店や土産物店が軒を連ねる市場。観光スポットとしても、地元民の飲みの場としても人気。',
                'img_ext' => 'jpg',
                'stamp_key' => $this->randKey(),
                'shows' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('keywords')->insert([
            [
                'spot_id' => 1,
                'keyword' => 'パンどろぼう',
            ],
            [
                'spot_id' => 2,
                'keyword' => 'ラスト・ソング・フォー・ユー',
            ],
            [
                'spot_id' => 3,
                'keyword' => 'ラスト・ソング・フォー・ユー',
            ],
            [
                'spot_id' => 3,
                'keyword' => '海がきこえる',
            ],
            [
                'spot_id' => 4,
                'keyword' => 'The Harimaya Bridge はりまや橋',
            ],
            [
                'spot_id' => 4,
                'keyword' => 'たいようのマキバオー',
            ],
            [
                'spot_id' => 4,
                'keyword' => 'クッキンアイドル アイ！マイ！まいん！',
            ],
            [
                'spot_id' => 4,
                'keyword' => 'ゴリパラ見聞録',
            ],
            [
                'spot_id' => 4,
                'keyword' => '南国土佐を後にして',
            ],
            [
                'spot_id' => 4,
                'keyword' => '海がきこえる',
            ],
            [
                'spot_id' => 4,
                'keyword' =>
                    '特捜戦隊デカレンジャー 20th ファイヤーボール・ブースター',
            ],
            [
                'spot_id' => 5,
                'keyword' => '所さんの学校では教えてくれないそこんトコロ！',
            ],
            [
                'spot_id' => 6,
                'keyword' => 'おへんろ。',
            ],
            [
                'spot_id' => 6,
                'keyword' => '東海オンエア',
            ],
        ]);
        DB::table('reviews')->insert([
            [
                'spot_id' => 2,
                'user_id' => 1,
                'rate' => 5,
                'comment' => '車窓から海が見えて最高でした！',
                'views' => 1,
                'ip_addr' => '192.0.2.11',
                'port' => 49211,
                'user_agent' =>
                    'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('coupons')->insert([
            [
                'spot_id' => 1,
                'name' => 'テストクーポン（実際には使用できません）',
                'cond_spot_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('user_coupons')->insert([
            [
                'coupon_id' => 1,
                'user_id' => 2,
                'key' => $this->randKey(),
                'is_used' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coupon_id' => 1,
                'user_id' => 5,
                'key' => $this->randKey(),
                'is_used' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('stamps')->insert([
            [
                'spot_id' => 2,
                'user_id' => 2,
                'ip_addr' => '192.0.2.21',
                'port' => 49231,
                'user_agent' =>
                    'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spot_id' => 2,
                'user_id' => 5,
                'ip_addr' => '192.0.2.22',
                'port' => 49231,
                'user_agent' =>
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('queries')->insert([
            [
                'user_id' => 2,
                'query' => 'この間にある観光スポットを推薦して',
                'from_spot_id' => 2,
                'to_spot_id' => 6,
                'ip_addr' => '192.0.2.31',
                'port' => 49231,
                'user_agent' =>
                    'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'query' => '「ものべの」の聖地はどこ？',
                'from_spot_id' => null,
                'to_spot_id' => null,
                'ip_addr' => '192.0.2.32',
                'port' => 49232,
                'user_agent' =>
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('photos')->insert([
            [
                'user_id' => 4,
                'lng' => $this->encodeLng(133.685047),
                'lat' => $this->encodeLat(33.607133),
                'img_ext' => 'jpg',
                'comment' =>
                    'ついに香美市に到着！あのゲームにも出てきた場所、土佐山田駅だ！',
                'ip_addr' => '192.0.2.41',
                'port' => 49241,
                'user_agent' =>
                    'Mozilla/5.0 (Android 15; Mobile; rv:146.0) Gecko/146.0 Firefox/146.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('api_keys')->insert([
            [
                'user_id' => 3,
                'name' => 'テスト用',
                'key' => hash(
                    'sha3-512',
                    'R9AnJFbw34/CWEQhwBVzDC4BGiAfM6IigWoU5pKm9L7y3zh6mzKEB32KjcyhPdq.d6hNo6+Iykh0EOVHQ/Wyp',
                    true,
                ),
                'ip_addr' => '192.0.2.51',
                'port' => 49251,
                'user_agent' =>
                    'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    private function randKey()
    {
        return rand(0, PHP_INT_MAX);
    }
}
