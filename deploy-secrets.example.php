<?php
/**
 * Template for deploy-secrets.php — copy this file to deploy-secrets.php
 * (same folder) and fill in real values. deploy-secrets.php is gitignored
 * and must be uploaded to the server by hand (FTP / hosting file manager),
 * never committed.
 *
 * DEPLOY_SECRET must exactly match the "Secret" field on the GitHub
 * webhook: repo Settings -> Webhooks -> the webhook pointing at deploy.php.
 *
 * MANUAL_DEPLOY_PASSWORD is the ?pass= value for manual-deploy.php.
 *
 * Generate strong values with: openssl rand -hex 24
 */

define( 'DEPLOY_SECRET', 'REPLACE_ME_WITH_A_LONG_RANDOM_VALUE' );
define( 'MANUAL_DEPLOY_PASSWORD', 'REPLACE_ME_WITH_A_LONG_RANDOM_VALUE' );
