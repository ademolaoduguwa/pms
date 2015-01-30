<?php
//JSON RESPONSE CONSTANTS
define('STATUS_OK', 1);
define('STATUS_ERROR', 2);
define('STATUS_ACCESS_DENIED', 3);
define('STATUS_NO_DATA', 4);

define('P_STATUS', 'status');
define('P_DATA', 'data');
define('P_MESSAGE', 'message');
define('P_ACCESS_TOKEN', 'access_token');

//ONLINE STATUS CODES
define('ONLINE', 1);
define('OFFLINE', 0);

//user type
define('PATIENT', 1);
define('STAFF', 2);
define('ADMIN', 3);

//staff roles
define('ROLES', 'roles');
define('EXISTING', 'existing');
define('AVAILABLE', 'available');
define('ADMINISTRATOR', 1);
define('DOCTOR', 2);
define('PHARMACIST', 3);

//ACTIVE STATUS CONSTANTS
define('ACTIVE', 1);
define('INACTIVE', 2);
define('UNCLEAR', 3);
define('CLEARED', 4);
define('PENDING', 5);
define('PROCESSING', 6);
define('COMPLETED', 7);