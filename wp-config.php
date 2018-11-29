<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'kubik');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'root');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'V0pXsZVzi9.5pbw-US3?fa~KXRlr}Jx6+*F(&[qn|Y xYAX~8~uQ>}kkRYE.#8[S');
define('SECURE_AUTH_KEY',  'ADP.<s3TGDCe;_l4^YIX8>]4Tufu}8oxW9h*Iq6.vT~N<7*w+c5nFE)*N`d1e~4U');
define('LOGGED_IN_KEY',    '>SXJhZ!qs+%:h<yw#*.#?*rD^OL XKEU;2MFq{20q!E2[xbuB~I8s6%;`-dmv6Ze');
define('NONCE_KEY',        'S1Ih1w)!.Jw<8B+x0_n;X.nQVoS%IkwUxx1/?%8G?:%BtcKmmt4seL`yMY[G*5oD');
define('AUTH_SALT',        '-C *q5W}(C&Am:6m7D;S13M&|n7I$&9zsvR=O8gi| R]8U%Lpkg`/I+}w^yQ}w*[');
define('SECURE_AUTH_SALT', 'pEWPU?Dwp3II$ YcL+z8o#QLLpu+vtRNmGwBR.:J(ni]cyWITS_$-M*G]:88E&Eq');
define('LOGGED_IN_SALT',   '.*#%u}IA!- ~Zq9Jhc`u.XcI {zt#w~%^J([yHm:/U :w)+G2g*w#{nXSPw [sxS');
define('NONCE_SALT',       '85DrMRa6c$ :4X#VeoSI3`g)[q_#]l2`}C#[x#+/m[DxaT7)2`FRKzTWV%67pgU^');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
