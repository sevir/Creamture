@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/codeception/codeception/codecept
php "%BIN_TARGET%" %*
