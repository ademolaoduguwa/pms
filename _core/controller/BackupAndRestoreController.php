<?php
/**
 * Created by PhpStorm.
 * User: petwho
 * Date: 4/21/15
 * Time: 12:02 PM
 */

class BackupAndRestoreController {
    private $systemBackupAndRestore;

    public function __construct(){
        $this->systemBackupAndRestore = new BackupAndRestoreModel();
    }

    public function backupDB(){
        return $this->systemBackupAndRestore->backupDB();
    }

    public function restoreDB($sqlDumpFileName, $sqlDumpFileTmpName){
        return $this->systemBackupAndRestore->restoreDB($sqlDumpFileName, $sqlDumpFileTmpName);
    }

    public function getFiles(){
        return $this->systemBackupAndRestore->getFiles();
    }

}
