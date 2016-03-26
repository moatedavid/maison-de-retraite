<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'ma-maisonderetraite');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+2-x!~|=a?gaVB+I$kg0FPkQcr;(.wu-L5F=rM|n+vp>>.rC+Ft_$[|N,[wNyFxE');
define('SECURE_AUTH_KEY',  '4x:F67Qiv?GHNh.$I)=nL=oy_9=S?$CBLA/k?]zF`?:qVsqw6KI$Z&c$AvO`s9fV');
define('LOGGED_IN_KEY',    '5L8v6j~:D~{Pg<m>s[6~k`{6exqUCX>!@,nlqV)V>}*`G#8=;XHN^M_nX~$>0!rI');
define('NONCE_KEY',        'KUb;:kG/re<U=+s)qAX9AmrD[}]K>8;V?o%s-u<G>cmyK B2R%+XI}/JT}h,J-bb');
define('AUTH_SALT',        'X.7E1=*/Xr+v7:CIsAc&k[V{Y|XaB_[wQ^YM.|^B:vuaCs+SI^e{q++Q+?xSo&|$');
define('SECURE_AUTH_SALT', '!+63O!^Pd~.W6al6BI~mYQ*`^-Icx~=2ESDKPUa!qdoq}uZ~)}>D2-6!k;/cb4>]');
define('LOGGED_IN_SALT',   'E4VoEnfJy[jf%1QFh2*^9.vLw>G}.M6d+-U`+|e iP|14tZ>~t_F>Ja1SZsr|e|;');
define('NONCE_SALT',       'THB*$Uk`~n>=[pl8.+Ruf7.oxbr,dhg!3vw(,m%j##hA`fi@5K2uM&JefcaX=Sj-');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'dm_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');