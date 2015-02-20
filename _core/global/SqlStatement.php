<?php
/**
 * SqlStatement.php
 * -------------
 * It should contain classes which contain constants that represent the SQL queries for that table.
 * in the following format
 * class <database_table>SqlStatement {
 *      ...
 * }
 */

class UserAuthSqlStatement {
        const VERIFY_USER = "SELECT COUNT(*) AS count FROM user_auth WHERE regNo = :regNo AND passcode = :passcode";
        const ADD = 'INSERT INTO user_auth (regNo, passcode, create_date, modified_date, status) VALUES (:regNo, SHA1(:passcode), NOW(), NOW(), :status)';
        const GET = 'SELECT userid, regNo, create_date, modified_date, status, online_status
                                FROM user_auth
                                WHERE regNo = :regNo AND userid = :userid';
        const GET_ALL = 'SELECT p.surname, p.firstname, p.middlename, p.userid, p.department_id, p.work_address, p.home_address, p.telephone, p.sex, p.birth_date, ua.regNo FROM profile as p RIGHT JOIN user_auth as ua ON (p.userid = ua.userid)';

        const CHANGE_PASSCODE = 'UPDATE user_auth SET passcode = SHA1(:passcode), status = :status, modified_date = NOW(), online_status = :online_status WHERE userid = :userid';
        const CHANGE_ONLINE_STATUS = 'UPDATE user_auth SET online_status = :online_status WHERE userid = :userid';
        const CHANGE_STATUS = 'UPDATE user_auth SET status = :status WHERE regNo = :regNo';
        const GET_USER_ROLE = 'SELECT u.userid, u.regNo, p.surname, p.firstname, p.middlename, pr.staff_role_id AS staff_role, pr.staff_permission_id AS staff_permission
            FROM user_auth AS u
                LEFT JOIN profile AS p
                    ON(u.userid = p.userid)
                LEFT JOIN permission_role AS pr
                    ON (p.userid = pr.userid)
            WHERE pr.staff_role_id = :staff_role_id
                    ORDER by p.surname, p.firstname, p.middlename';

        const UPDATE_STATUS = 'UPDATE user_auth SET status = :status WHERE userid = :userid LIMIT 1';
        const GET_USER_BY_CREDENTIALS = "SELECT u.userid, u.regNo, u.status, u.online_status, p.profile_id, p.surname, p.firstname, p.middlename, p.department_id, p.sex
            FROM user_auth AS u
                LEFT JOIN PROFILE AS p
                    ON(u.userid=p.userid)
            WHERE regNo=:regNo AND passcode=SHA1(:passcode)";

        const GET_USER_BY_ID = 'SELECT u.userid, u.regNo, u.online_status, p.profile_id, p.surname, p.firstname, p.middlename, p.department_id, p.sex
        FROM user_auth AS u
            LEFT JOIN PROFILE AS p
                ON(u.userid=p.userid)
        WHERE u.userid=:userid';

        const FLAG_USER_ONLINE = 'UPDATE user_auth SET online_status = 1 WHERE userid=:userid';

        const FLAG_USER_OFFLINE = 'UPDATE user_auth SET online_status = 0 WHERE userid=:userid';

        const GET_BY_REGNO = 'SELECT userid FROM user_auth WHERE regNo=:regNo';

        const GET_STATUS = "SELECT status FROM user_auth WHERE userid = :userid";
}

class ProfileSqlStatement {
        const ADD = 'INSERT INTO profile (userid, surname, firstname, middlename, department_id, work_address, home_address, telephone, sex,
                                        height, weight, birth_date, create_date, modified_date)
                                 VALUES (:userid, LOWER(:surname), LOWER(:firstname), LOWER(:middlename), :department_id, LOWER(:work_address), LOWER(:home_address), :telephone, :sex,
                                                        :height, :weight, :birth_date, NOW(), NOW()) ';
        const GET = 'SELECT ua.regNo, p.userid, surname, firstname, middlename, department_id, work_address, home_address, telephone, sex, height, weight, birth_date
            FROM profile AS p
                INNER JOIN user_auth AS ua
                    ON p.userid = ua.userid
            WHERE regNo = :regNo';
        const UPDATE = 'UPDATE profile SET surname = :surname, firstname = :firstname, middlename = :middlename, work_address = :work_address, home_address = :home_address, telephone = :telephone, sex = :sex, height = :height, weight = :weight,department_id = :department_id, birth_date = :birth_date, modified_date = NOW() WHERE userid = :userid';
        const UPDATE_BASIC_INFO = 'UPDATE profile SET surname = LOWER(:surname), firstname = LOWER(:firstname), middlename = LOWER(:middlename), sex = :sex, birth_date = :birth_date, modified_date = now() WHERE userid = :userid';
        const GET_PROFILE = 'SELECT ua.regNo, p.userid, p.surname, p.firstname, p.middlename, p.department, p.work_address, p.home_address, p.telephone, p.sex,
                                        p.height, p.weight, p.birth_date, p.create_date, p.modified_date FROM profile as p LEFT JOIN user_auth as ua ON(p.userid = ua.userid) WHERE ua.regNo = :regNo';
        const GET_USER_AND_DEPT = 'SELECT dept.department_name, p.firstname, p.middlename, p.surname, FROM PROFILE AS p
                                   LEFT JOIN department AS dept
                                    ON p.department_id = dept.department_id
                                    WHERE p.active_fg = 1
                                    GROUP BY department_name';
}

class PermissionRoleSqlStatement {
        const DELETE_STAFF_ROLE = 'UPDATE permission_role SET active_fg = 0, modified_date = NOW() WHERE permission_role_id = :permission_role_id';
        const UPDATE_ROLE_PERMISSION = 'UPDATE permission_role SET staff_permission_id = :staff_permission_id, modified_date = NOW() WHERE permission_role_id = :permission_role_id';
        const ADD_STAFF_ROLE = 'INSERT INTO permission_role (userid, staff_permission_id, staff_role_id, create_date, modified_date, active_fg)
                                                        VALUES (:userid, :staff_permission_id, :staff_role_id, NOW(), NOW(), 1)';
        const GET_STAFF_ROLE = "SELECT pr.permission_role_id, pr.staff_role_id, pr.staff_permission_id, pr.userid, sr.role_label, sp.staff_permission FROM permission_role AS pr INNER JOIN staff_role AS sr on pr.staff_role_id = sr.staff_role_id INNER JOIN staff_permission AS sp ON pr.staff_permission_id = sp.staff_permission_id WHERE pr.userid = :userid AND pr.active_fg = 1";
        const GET_ALL_ROLES = "SELECT staff_role_id, role_label FROM staff_role";
        const GET_ALL_PERMISSIONS = "SELECT staff_permission_id, staff_permission FROM staff_permission";
        const CHECK_PERMISSION = "SELECT COUNT(*) AS count FROM staff_permission WHERE staff_permission_id = :staff_permission_id";
        const CHECK_ROLE = "SELECT COUNT(*) AS count FROM staff_role WHERE staff_role_id = :staff_role_id";
}

class PatientSqlStatement {
        const ADD = 'INSERT INTO patient (surname, firstname, middlename, regNo, home_address, telephone, sex, height, weight, birth_date, nok_firstname, nok_middlename, nok_surname, nok_address, nok_telephone, nok_relationship,
                                          citizenship,  religion,  family_position,  mother_status,  father_status,    marital_status,  no_of_children, create_date, modified_date)
                                 VALUES (LOWER(:surname), LOWER(:firstname), LOWER(:middlename), :regNo, :home_address, :telephone, :sex, :height, :weight, :birth_date, :nok_firstname, :nok_middlename, :nok_surname, :nok_address, :nok_telephone, :nok_relationship,
                                          :citizenship,  :religion,  :family_position,  :mother_status,  :father_status,    :marital_status,  :no_of_children, NOW(), NOW() )';

        const GET = 'SELECT surname, firstname, middlename, regNo, home_address, telephone, sex, height, weight, birth_date, nok_firstname, nok_middlename, nok_surname, nok_address, nok_telephone, nok_relationship, create_date, modified_date
                                    FROM patient WHERE patient_id = :patient_id';
        const UPDATE = 'UPDATE patient SET surname = LOWER(:surname), firstname = LOWER(:firstname), middlename = LOWER(:middlename), regNo = :regNo, home_address = :home_address, telephone = :telephone, sex = :sex, height = :height, weight = :weight, birth_date = :birth_date, nok_firstname = :nok_firstname, nok_middlename = :nok_middlename, nok_surname = :nok_surname, nok_address = :nok_address, nok_telephone = :nok_telephone, nok_relationship = :nok_relationship, modified_date = NOW()';

        const GET_ALL = 'SELECT patient_id, surname, firstname, middlename, regNo, home_address, telephone, sex, height, weight, birth_date, nok_firstname, nok_middlename, nok_surname, nok_address, nok_telephone, nok_relationship, create_date, modified_date
                                    FROM patient';

        const SEARCH = "SELECT p.patient_id, p.surname, p.middlename, p.regNo, p.sex, pq.active_fg AS queue_status
            FROM patient AS p
                LEFT JOIN patient_queue AS pq
                    ON p.patient_id = pq.patient_id
            WHERE surname LIKE :wildcard
            OR firstname LIKE :wildcard
            OR middlename LIKE :wildcard
            OR regNo = :parameter";
}

class PatientQueueSqlStatement {
    const ADD = "INSERT INTO patient_queue (patient_id, doctor_id, active_fg, create_date, modified_date) VALUES (:patient_id, :doctor_id, 1, NOW(), NOW())";

    const REMOVE = "UPDATE patient_queue SET active_fg = 0, modified_date = NOW() WHERE patient_id = :patient_id";

    const ONLINE_DOCTORS = "SELECT ua.userid, ua.online_status, p.surname, p.firstname, p.middlename
        FROM user_auth AS ua
            LEFT JOIN profile AS p
                ON ua.userid = p.userid
            LEFT JOIN permission_role AS pr
                ON ua.userid = pr.userid
        WHERE ua.online_status = 1
        AND pr.staff_role_id = 2
        AND pr.active_fg = 1
        AND ua.status = 1
        AND ua.active_fg = 1";

    const OFFLINE_DOCTORS_WITH_QUEUE = "SELECT ua.userid, ua.online_status, p.surname, p.firstname, p.middlename
        FROM patient_queue AS pq
            INNER JOIN profile AS p
                ON pq.doctor_id = p.userid
            INNER JOIN user_auth AS ua
                ON ua.userid = pq.doctor_id
        WHERE pq.active_fg = 1
        AND ua.online_status != 1";

    const DOCTOR_QUEUE = "SELECT p.patient_id, p.surname, p.firstname, p.middlename, p.regNo, p.sex
        FROM patient_queue AS pq
            INNER JOIN patient AS p
                ON pq.patient_id = p.patient_id
        WHERE pq.active_fg = 1
        AND p.active_fg = 1
        AND pq.doctor_id = :doctor_id";

    const GET_LAST_MODIFIED_TIME = "SELECT MAX(modified_date) AS LMT FROM patient_queue";

    const PATIENT_ON_QUEUE = "SELECT COUNT(*) AS count FROM patient_queue WHERE patient_id = :patient_id AND active_fg = 1";

    const CHANGE_IN_QUEUE = "SELECT COUNT(*) AS count FROM patient_queue WHERE modified_date > :modified_date";
}

class RosterSqlStatement {
    const ADD = 'INSERT INTO roster (user_id, created_by, dept_id, duty, duty_date, created_date, modified_date)
                    VALUES (:user_id, :created_by, :dept_id, :duty, :duty_date, now(), now())';
    const GET_BY_ID = 'SELECT user_id, created_by, dept_id, duty, duty_date, created_date, modified_date, modified_by
                FROM roster WHERE roster_id=:roster_id';

    const GET_ALL = 'SELECT r.roster_id, r.user_id, r.created_by, r.dept_id, r.duty, r.duty_date, r.created_date, r.modified_date, r.modified_by, p.surname, p.firstname, r.user_id, p.middlename
                FROM roster AS r INNER JOIN PROFILE AS p ON p.userid = r.user_id WHERE r.active_fg = 1';
    const GET_BY_STAFF_ID = 'SELECT r.roster_id, r.user_id, r.created_by, r.dept_id, r.duty, r.duty_date, r.created_date, r.modified_date, r.modified_by, p.surname, p.firstname, r.user_id, p.middlename
                FROM roster AS r INNER JOIN PROFILE AS p ON p.userid = r.user_id WHERE r.user_id = :user_id AND r.active_fg = 1';
    const GET_BY_DOCTOR = 'SELECT user_id, created_by, dept_id, duty, duty_date, created_date, modified_date, modified_by
                FROM roster WHERE user_id=:user_id';
    const UPDATE = 'UPDATE roster SET  duty_date=:duty_date, modified_date= now(), modified_by=:modified_by
                        WHERE roster_id = :roster_id';
    const DELETE_ROSTER = 'UPDATE roster SET active_fg = 0, modified_date = now(), modified_by = :modified_by
                            WHERE roster_id = :roster_id';
}

class DepartmentSqlStatment{
    const GET_ALL = 'SELECT department_id, department_name FROM department';
}

class CommunicationSqlStatement {
    const GET_INBOX = "SELECT profile.surname, profile.middlename, profile.firstname, msg_id, sender_id, msg_subject, msg_body, msg_status, communication.created_date 
        FROM communication 
            INNER JOIN profile 
                ON communication.sender_id = profile.userid
        WHERE recipient_id = :recipient_id 
        ORDER BY communication.created_date DESC
        LIMIT @offset, @count";

    const GET_SENT_MESSAGES = "SELECT profile.surname, profile.middlename, profile.firstname, msg_id, recipient_id, msg_subject, msg_body, communication.created_date
        FROM communication 
            INNER JOIN profile 
                ON communication.recipient_id = profile.userid
        WHERE sender_id = :sender_id 
        ORDER BY communication.created_date DESC
        LIMIT @offset, @count";

    const COUNT_INBOX = "SELECT COUNT(*) AS count 
        FROM communication 
            INNER JOIN profile 
                ON communication.sender_id = profile.userid 
        WHERE recipient_id = :recipient_id";

    const COUNT_SENT = "SELECT COUNT(*) AS count
        FROM communication
            INNER JOIN profile
                ON communication.recipient_id = profile.userid
        WHERE sender_id = :sender_id";

    const SEND_MESSAGE = "INSERT INTO communication (sender_id, recipient_id, msg_subject, msg_body, msg_status, active_fg, created_date, modified_date) VALUES (:sender_id, :recipient_id, :msg_subject, :msg_body, 1, 1, NOW(), NOW())";

    const CHECK_INBOX_MESSAGE = "SELECT COUNT(*) AS count FROM communication WHERE msg_id = :msg_id AND recipient_id = :recipient_id";

    const CHECK_SENT_MESSAGE = "SELECT COUNT(*) AS count FROM communication WHERE msg_id = :msg_id AND sender_id = :sender_id";

    const GET_INBOX_MESSAGE = "SELECT CONCAT_WS(' ', profile.surname, profile.middlename, profile.firstname) AS sender_name, msg_id, sender_id, msg_subject, msg_body, msg_status 
        FROM communication
            INNER JOIN profile
                ON communication.sender_id = profile.userid
        WHERE recipient_id = :recipient_id
        AND msg_id = :msg_id";

    const GET_SENT_MESSAGE = "SELECT CONCAT_WS(' ', profile.surname, profile.middlename, profile.firstname) AS recipient_name, msg_id, recipient_id, msg_subject, msg_body
        FROM communication
            INNER JOIN profile
                ON communication.recipient_id = profile.userid
        WHERE sender_id = :sender_id
        AND msg_id = :msg_id";

    const MARK_AS_READ = "UPDATE communication SET msg_status = 0, modified_date = NOW() WHERE msg_id = :msg_id AND recipient_id = :recipient_id";

    const MARK_AS_UNREAD = "UPDATE communication SET msg_status = 1, modified_date = NOW() WHERE msg_id = :msg_id AND recipient_id = :recipient_id";

    const CHECK_NEW_MESSAGE = "SELECT COUNT(*) AS count FROM communication WHERE created_date > :created_date AND recipient_id = :recipient_id";
}

class PrescriptionSqlStatement{
    const GET_PRESCRIPTION = "SELECT * FROM prescription AS p WHERE p.treatment_id = :treatment_id";
    const GET_QUEUE = "SELECT t.treatment_id, t.patient_id, pa.firstname, pa.surname, pa.middlename, pa.regNo FROM
                      treatment AS t INNER JOIN prescription as p ON (t.treatment_id = p.treatment_id)  INNER JOIN
                      patient as pa ON (t.patient_id = pa.patient_id) WHERE p.status = :status GROUP BY t.treatment_id";
    const UPDATE_STATUS = "UPDATE prescription AS p SET p.status = :status WHERE prescription_id = :prescription_id";
    const PRESCRIPTION_DRUG = "INSERT INTO outgoing_drugs AS od ";
}

class DrugSqlStatement{
    const GET = "SELECT drug_ref_id, name drug FROM drug_ref";
    const GET_DRUG_ID = "SELECT d.drug_ref_id FROM drug_ref AS d WHERE d.name = :name";
    const ADD_DRUG = "INSERT INTO drug_ref ('name', 'created_date') VALUES (:name, NOW())";
}

class VitalsSqlStatement {
    const ADD = "INSERT INTO vitals (patient_id, encounter_id, added_by, temp, pulse, respiratory_rate, blood_pressure, height, weight, bmi, active_fg, created_date) VALUES (:patient_id, :encounter_id, :added_by, :temp, :pulse, :respiratory_rate, :blood_pressure, :height, :weight, :bmi, 1, NOW())";
    const GET_VITALS = "SELECT patient_id, encounter_id, added_by, temp, pulse, respiratory_rate, blood_pressure, height, weight, bmi, created_date FROM vitals WHERE patient_id = :patient_id";
}