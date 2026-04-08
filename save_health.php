<?php
include 'db.php';

// Upsert the employee basic info + the full multi-step health profile.
// The health form data is stored as JSON in `employee_health_profiles.profile_data`.
function createProfileTableIfNeeded(mysqli $conn): void {
    $conn->query("
        CREATE TABLE IF NOT EXISTS employee_health_profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL UNIQUE,
            class_type VARCHAR(50) NULL,
            profile_data LONGTEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (employee_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

function postStr(string $key, $default = ''): string {
    if (!isset($_POST[$key])) return (string)$default;
    return trim((string)$_POST[$key]);
}

function postInt(string $key, int $default = 0): int {
    if (!isset($_POST[$key])) return $default;
    return intval($_POST[$key]);
}

function postBool(string $key): bool {
    return isset($_POST[$key]) && $_POST[$key] !== '0' && $_POST[$key] !== '';
}

if (isset($_POST['save'])) {
    createProfileTableIfNeeded($conn);

    $employee_id = postInt('employee_id', 0);

    // --- Personal profile (stored in employees + also in JSON) ---
    $full_name = postStr('full_name');
    $birthday = postStr('birthday');
    $age = postInt('age', 0);
    $gender = postStr('gender');
    $contact_number = postStr('contact_number');
    $religion = postStr('religion');
    $occupation = postStr('occupation');
    $civil_status = postStr('civil_status');
    $home_address = postStr('home_address');

    // Persist personal fields into `employees` (your existing table).
    // If adding a new profile, insert first then use the new id for the health profile row.
    if ($employee_id > 0) {
        $stmt = $conn->prepare("UPDATE employees SET name=?, age=?, sex=?, birthday=?, address=?, contact=?, department=?, civil_status=? WHERE id=?");
        $stmt->bind_param(
            "sissssssi",
            $full_name,
            $age,
            $gender,
            $birthday,
            $home_address,
            $contact_number,
            $occupation,
            $civil_status,
            $employee_id
        );
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO employees (name, age, sex, birthday, address, contact, department, civil_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssss", $full_name, $age, $gender, $birthday, $home_address, $contact_number, $occupation, $civil_status);
        $stmt->execute();
        $employee_id = $stmt->insert_id;
        $stmt->close();
    }

    // --- Medical classification + medical findings ---
    $medical_class = postStr('medical_class'); // e.g. "Class A"
    $medical_condition_findings = postStr('medical_condition_findings');

    // --- Emergency ---
    $emergency_complete_name = postStr('emergency_complete_name');
    $emergency_relationship = postStr('emergency_relationship');
    $emergency_address = postStr('emergency_address');
    $emergency_contact_number = postStr('emergency_contact_number');

    // --- Personal/social history ---
    $smoking = postStr('smoking');
    $pack_day_years = postStr('pack_day_years');
    $alcohol_drinking = postStr('alcohol_drinking');
    $alcohol_type_frequency = postStr('alcohol_type_frequency');
    $illegal_drug_use = postStr('illegal_drug_use');
    $illegal_drug_use_specify = postStr('illegal_drug_use_specify');
    $sexually_active = postStr('sexually_active');
    $no_of_sexual_partners = postInt('no_of_sexual_partners', 0);
    $partners = postStr('partners');

    // --- Past medical history (checkboxes) ---
    $allergy_specify_type = postStr('allergy_specify_type');

    $past_medical = [
        'allergy' => postBool('past_allergy'),
        'allergy_specify_type' => $allergy_specify_type,
        'asthma' => postBool('past_asthma'),
        'cancer' => postBool('past_cancer'),
        'coronary_artery_disease' => postBool('past_coronary_artery_disease'),
        'hypertension_elevated_bp' => postBool('past_hypertension_elevated_bp'),
        'congenital_heart_disorder' => postBool('past_congenital_heart_disorder'),
        'peptic_ulcer' => postBool('past_peptic_ulcer'),
        'psychological_disorder' => postBool('past_psychological_disorder'),
        'psychological_disorder_specify' => postStr('psychological_disorder_specify'),
        'thyroid_disease' => postBool('past_thyroid_disease'),
        'pcos' => postBool('past_pcos'),
        'epilepsy_seizure_disorder' => postBool('past_epilepsy_seizure_disorder'),
        'skin_disorder' => postBool('past_skin_disorder'),
        'tuberculosis' => postBool('past_tuberculosis'),
        'hepatitis' => postBool('past_hepatitis'),
        'other_findings' => postStr('other_findings'),
    ];

    // --- Immunizations (COVID-19) ---
    $immunized_against_covid_19 = postStr('immunized_against_covid_19'); // Yes/No
    $covid_brand = postStr('covid_brand');
    $covid_1st_dose = postStr('covid_1st_dose');
    $covid_2nd_dose = postStr('covid_2nd_dose');
    $covid_1st_booster = postStr('covid_1st_booster');
    $covid_2nd_booster = postStr('covid_2nd_booster');
    $unvaccinated_reason = postStr('unvaccinated_reason');
    $other_immunizations = postStr('other_immunizations');
    $physical_notes_other_findings = postStr('physical_notes_other_findings');

    $profile = [
        'personal' => [
            'full_name' => $full_name,
            'birthday' => $birthday,
            'age' => $age,
            'gender' => $gender,
            'contact_number' => $contact_number,
            'religion' => $religion,
            'occupation' => $occupation,
            'civil_status' => $civil_status,
            'home_address' => $home_address,
        ],
        'emergency' => [
            'complete_name' => $emergency_complete_name,
            'relationship' => $emergency_relationship,
            'address' => $emergency_address,
            'contact_number' => $emergency_contact_number,
        ],
        'medical' => [
            'class_type' => $medical_class,
            'medical_condition_findings' => $medical_condition_findings,
        ],
        'personal_social_history' => [
            'smoking' => $smoking,
            'pack_day_years' => $pack_day_years,
            'alcohol_drinking' => $alcohol_drinking,
            'alcohol_type_frequency' => $alcohol_type_frequency,
            'illegal_drug_use' => $illegal_drug_use,
            'illegal_drug_use_specify' => $illegal_drug_use_specify,
            'sexually_active' => $sexually_active,
            'no_of_sexual_partners' => $no_of_sexual_partners,
            'partners' => $partners,
        ],
        'past_medical_history' => $past_medical,
        'immunizations' => [
            'immunized_against_covid_19' => $immunized_against_covid_19,
            'covid_brand' => $covid_brand,
            'covid_1st_dose' => $covid_1st_dose,
            'covid_2nd_dose' => $covid_2nd_dose,
            'covid_1st_booster' => $covid_1st_booster,
            'covid_2nd_booster' => $covid_2nd_booster,
            'unvaccinated_reason' => $unvaccinated_reason,
            'other_immunizations' => $other_immunizations,
            'physical_notes_other_findings' => $physical_notes_other_findings,
        ],
        // Optional step VI fields, if present in the form
        'hospital_admission' => [
            'hospital_admission_diagnosis1' => postStr('hospital_admission_diagnosis1'),
            'hospital_admission_when1' => postStr('hospital_admission_when1'),
            'hospital_admission_diagnosis2' => postStr('hospital_admission_diagnosis2'),
            'hospital_admission_when2' => postStr('hospital_admission_when2'),
            'past_surgical_operation1_type' => postStr('past_surgical_operation1_type'),
            'past_surgical_operation1_when' => postStr('past_surgical_operation1_when'),
            'disability' => postStr('disability'),
        ],
    ];

    $profile_json = json_encode($profile, JSON_UNESCAPED_UNICODE);

    // --- Upsert into employee_health_profiles ---
    $exists = $conn->query("SELECT id FROM employee_health_profiles WHERE employee_id=" . intval($employee_id));
    if ($exists && $exists->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE employee_health_profiles SET class_type=?, profile_data=? WHERE employee_id=?");
        $stmt->bind_param("ssi", $medical_class, $profile_json, $employee_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO employee_health_profiles (employee_id, class_type, profile_data) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $employee_id, $medical_class, $profile_json);
        $stmt->execute();
        $stmt->close();
    }

    // Backward compatibility: save to the original simple table if those columns exist + fields were posted.
    // (Ignore failures so the newer JSON-based flow still works.)
    try {
        if (isset($_POST['blood_type']) || isset($_POST['height']) || isset($_POST['weight']) || isset($_POST['allergies']) || isset($_POST['conditions'])) {
            $eid = $employee_id;
            $b = postStr('blood_type');
            $h = postStr('height');
            $w = postStr('weight');
            $a = postStr('allergies');
            $c = postStr('conditions');
            $conn->query("INSERT INTO employee_health (employee_id, blood_type, height, weight, allergies, conditions)
                VALUES (" . intval($eid) . ", '" . $conn->real_escape_string($b) . "', '" . $conn->real_escape_string($h) . "', '" . $conn->real_escape_string($w) . "',
                    '" . $conn->real_escape_string($a) . "', '" . $conn->real_escape_string($c) . "')
                ON DUPLICATE KEY UPDATE
                    blood_type=VALUES(blood_type),
                    height=VALUES(height),
                    weight=VALUES(weight),
                    allergies=VALUES(allergies),
                    conditions=VALUES(conditions)
            ");
        }
    } catch (Throwable $e) {
        // no-op
    }

    header("Location: employees.php");
    exit();
}
?>