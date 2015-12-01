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

TODO:
create controller templates for gii
