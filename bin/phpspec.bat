@ECHO OFF
SET BIN_TARGET=%~dp0\"../vendor/phpspec/phpspec2/bin"\phpspec
php "%BIN_TARGET%" %*
