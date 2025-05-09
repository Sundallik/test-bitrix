<?php
use Bitrix\Main\Loader;

if (!Loader::includeModule('dev.site')) {
    return;
}

Loader::registerAutoLoadClasses('dev.site', [
    'DevSite\lib\Handlers\IBlock' => 'lib/Handlers/IBlock.php',
    'DevSite\lib\Agents\IBlock' => 'lib/Agents/IBlock.php',
]);

AddEventHandler(
    'iblock',
    'OnAfterIBlockElementAdd',
    ['DevSite\lib\Handlers\Iblock', 'OnAfterIBlockElementAddHandler']
);
AddEventHandler(
    'iblock',
    'OnAfterIBlockElementUpdate',
    ['DevSite\lib\Handlers\Iblock', 'OnAfterIBlockElementUpdateHandler']
);

\CAgent::AddAgent(
    "DevSite\lib\Agents\Iblock::cleanUpOldLogs();",
    "dev.site",
    "N",
    3600,
    "",
    "Y",
    ConvertTimeStamp(time() + 3600, "FULL")
);