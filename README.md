yii2actions
===========
<pre>
Экшены по умолчанию для yii2. Что б не писать однообразные экшены и вьюшки к ним

Uses:

class UsersController extends andkon\yii2actions\Controller
{
    protected $model = '\app\modules\users\models\Users';
}

goto http://site/index.php?r=users/ [index|update|create|view]

в контроллерах больше не надо писать однообразные функции actionIndex, actionUpdate и тд.
не требуется делать однообразные вьюшки на разные модели.
При необходимости и экшены и вьюшки переопределяются путем их добавления.

------------------
миграции используют webtoucher/yii2-migrate для разделения миграций на модули
uses:
class m000000_000000_users extends \andkon\yii2actions\Migration
{
    protected $tables = [
        'users' => [
            'id'       => 'pk',
            'login'    => 'varchar(255) not null',
            'group_id' => 'int',
            'password' => 'varchar(255) not null',
            'name'     => 'varchar(255) not null',
            'email'    => 'varchar(255) not null',
            'token'    => 'varchar(255)',
            'status'   => 'tinyint not null default 0',
            'created'  => 'timestamp not null default CURRENT_TIMESTAMP',
            'updated'  => 'timestamp',
        ],
    ];
    protected $foreignKeys = [
        [
            'from' => ['users', 'group_id'],
            'to'   => ['users_group', 'id'],
        ],
    ];
    protected $vals = [
        'users' => [
            ['id' => 1, 'login' => 'admin', 'password' => '123', 'name' => 'Admin', 'status' => \app\modules\users\Helper::STATUS_ADMIN],
        ],
    ];
}
</pre>
------------------

TODO:
create controller templates for gii
