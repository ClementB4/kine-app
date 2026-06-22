<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Workplace;
use App\Entity\User;
use App\Entity\Pathology;
use \App\Entity\Exercise;
use \App\Enum\ExerciseCategory;
use \App\Entity\UserWorkplace;
use \App\Entity\Patient;
use \App\Entity\PatientCase;
use \App\Enum\PatientCaseStatus;
use \App\Entity\PatientCasePathology;
use \App\Entity\PatientCasePhysio;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }
    
    public function load(ObjectManager $manager): void
    {

        // Arrays to hold the created objects for use in link tables (e.g., relationships)
        $workplacesObjects = [];
        $usersObjects = [];
        $patientsObjects = [];
        $pathologiesObjects = [];
        $exercisesObjects = [];

        // Create and persist workplaces
        $workplaces = [
            ['Keops', '111 Boulevard Soult', '75012', 'Paris'],
        ];

        foreach ($workplaces as $wp) {
            $workplace = new Workplace();
            $workplace->setName($wp[0]);
            $workplace->setAddress($wp[1]);
            $workplace->setPostalCode($wp[2]);
            $workplace->setTown($wp[3]);
            $workplace->setCreatedAt(new \DateTimeImmutable());
            $workplace->setUpdatedAt(new \DateTimeImmutable());
            
            $workplacesObjects[] = $workplace; // Store the created workplace object for later use

            $manager->persist($workplace);
        }
        
        // Create and persist users
        $users = [
            ['admin@example.com', 'admin', 'User', ['ROLE_SUPER_ADMIN'], true],
            ['kine1@example.com', 'Sophie', 'Bodiguel', ['ROLE_PHYSIO'], true],
            ['kine2@example.com', 'Jean', 'Dupont', ['ROLE_PHYSIO'], true],
            ['patient1@example.com', 'Marie', 'Dubois', ['ROLE_PATIENT'], true]
        ];

        foreach ($users as $u) {
            $user = new User();
            $user->setEmail($u[0]);
            $user->setFirstname($u[1]);
            $user->setLastname($u[2]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'kine'));
            $user->setRoles($u[3]);
            $user->setIsActive($u[4]);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());

            $usersObjects[] = $user; // Store the created user object for later use

            $manager->persist($user);
        }

        // Create and persist patients
        $patients = [
            ['Marie', 'Dubois', 'F', new \DateTimeImmutable('1990-01-01'), 165, 60, 'Sportive pro', 'Handball', 'Droit', $usersObjects[3]], // Link to the patient user created above
            ['Paul', 'Martin', 'M', new \DateTimeImmutable('1985-05-15'), 180, 80, 'Gendarme', 'Aucun', 'Gauche', null], // No linked user
            ['Lucie', 'Lemoine', 'F', new \DateTimeImmutable('1995-09-30'), 170, 65, 'Sportive', 'Course à pied', 'Droit', null], // No linked user
        ];

        foreach ($patients as $p) {
            $patient = new Patient();
            $patient->setFirstname($p[0]);
            $patient->setLastname($p[1]);
            $patient->setGender($p[2]);
            $patient->setBirthDate($p[3]);
            $patient->setHeight($p[4]);
            $patient->setWeight($p[5]);
            $patient->setJob($p[6]);
            $patient->setSport($p[7]);
            $patient->setLaterality($p[8]);
            $patient->setUser($p[9]);
            $patient->setCreatedAt(new \DateTimeImmutable());
            $patient->setUpdatedAt(new \DateTimeImmutable());

            $patientsObjects[] = $patient; // Store the created patient object for later use

            $manager->persist($patient);
        }

        // Create and persist pathologies
        $patholgies = [
            ['Aponévrosite plantaire réactive', 'Test commentaire', 28],
            ['Entorse LCA', null, 365],
            ['LMA ischios grade 0-1', null, 7],
            ['Dorsalgie aigue', null, 28],
            ['Cervicalgie chronique', null, 42],
        ];

        foreach ($patholgies as $p) {
            $patho = new Pathology();
            $patho->setName($p[0]);
            $patho->setDescription($p[1]);
            $patho->setEstimatedRecoveryDays($p[2]);
            $patho->setCreatedAt(new \DateTimeImmutable());
            $patho->setUpdatedAt(new \DateTimeImmutable());

            $pathologiesObjects[] = $patho; // Store the created pathology object for later use

            $manager->persist($patho);
        }

        // Create and persist exercises
        $exercises = [
            ['Renforcement gastrocménien proximal', null, 'https://www.youtube.com/watch?v=XaUbBeBwAyc&pp=ygULa2VvcHMga2luw6k%3D', ExerciseCategory::ANKLE],
            ['Renforcement des fléchisseurs du genou', null, 'https://www.youtube.com/watch?v=UMLX9pHkLSo&pp=ygULa2VvcHMga2luw6k%3D', ExerciseCategory::ANKLE],
            ['Progression saut vertical pogo', null, 'https://www.youtube.com/watch?v=g2cuoGrfXIU', ExerciseCategory::COD],
            ['Extension lombaire', null, 'https://www.youtube.com/watch?v=xiiQl47UY0w&pp=ygULa2VvcHMga2luw6k%3D', ExerciseCategory::CORE],
            ['Progression gamme athlétique AB', null, 'https://www.youtube.com/watch?v=aNB7G_wlWIo', ExerciseCategory::GAMMES],
            ['One leg bridge', null, 'https://www.youtube.com/watch?v=bFUiSEA-ji0&pp=ygULa2VvcHMga2luw6k%3D', ExerciseCategory::GAMMES],
            ['Excentrique ischios debout', null, 'https://www.youtube.com/watch?v=fASJnZ8YMOw&pp=ygULa2VvcHMga2luw6k%3D', ExerciseCategory::HIP],
            ['Ischios chaise romaine concentrique', null, 'https://www.youtube.com/watch?v=kAPNBhBKDsc', ExerciseCategory::KNEE],
            ['projection latérale en coup de poing à une main', null, 'https://www.youtube.com/watch?v=bu98449T6EI', ExerciseCategory::MB],
            ['ponçage du perioste', null, 'https://www.youtube.com/watch?v=1zsP961j3Sc', ExerciseCategory::PONCAGE],
        ];

        foreach ($exercises as $e) {
            $exercise = new Exercise();
            $exercise->setName($e[0]);
            $exercise->setDescription($e[1]);
            $exercise->setVideoUrl($e[2]);
            $exercise->setCategory($e[3]);
            $exercise->setCreatedAt(new \DateTimeImmutable());
            $exercise->setUpdatedAt(new \DateTimeImmutable());

            $exercisesObjects[] = $exercise; // Store the created exercise object for later use

            $manager->persist($exercise);
        }

        // Create relationships (e.g., UserWorkplace, PathologyExercise) here if needed, using the stored objects in the arrays above
        // Add relationships between users and workplaces
        // Sophie Bodiguel is linked to Keops
        $userWorkplace = new UserWorkplace();
        $userWorkplace->setUser($usersObjects[1]); // Second user (Sophie Bodiguel) is linked to the workplace
        $userWorkplace->setWorkplace($workplacesObjects[0]); // First workplace (Keops) is linked to the user
        $userWorkplace->setIsActive(true);
        $userWorkplace->setCreatedAt(new \DateTimeImmutable());
        $userWorkplace->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($userWorkplace);

        // Jean Dupont is linked to Keops
        $userWorkplace2 = new UserWorkplace();
        $userWorkplace2->setUser($usersObjects[2]); // Third user (Jean Dupont) is linked to the workplace
        $userWorkplace2->setWorkplace($workplacesObjects[0]); // First workplace (Keops) is linked to the user
        $userWorkplace2->setIsActive(true);
        $userWorkplace2->setCreatedAt(new \DateTimeImmutable());
        $userWorkplace2->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($userWorkplace2);

        // Add relationship for patientCase
        // Marie Dubois started 15 days ago
        $patientCase = new PatientCase();
        $patientCase->setPatient($patientsObjects[0]); // First patient (Marie Dubois) is linked to the pathology
        $patientCase->setCreatedAt(new \DateTimeImmutable());
        $patientCase->setUpdatedAt(new \DateTimeImmutable());
        $patientCase->setStartedAt(new \DateTimeImmutable('-15 days')); // Started 15 days ago
        $patientCase->setStatus(PatientCaseStatus::ACTIVE); // Set status to ACTIVE

        $manager->persist($patientCase);

        // Paul Martin started 30 days ago
        $patientCase2 = new PatientCase();
        $patientCase2->setPatient($patientsObjects[1]); // Second patient (Paul Martin) is linked to the pathology
        $patientCase2->setCreatedAt(new \DateTimeImmutable());
        $patientCase2->setUpdatedAt(new \DateTimeImmutable());
        $patientCase2->setStartedAt(new \DateTimeImmutable('-30 days')); // Started 30 days ago
        $patientCase2->setStatus(PatientCaseStatus::ACTIVE); // Set status to ACTIVE

        $manager->persist($patientCase2);

        // Lucie Lemoine started 10 days ago
        $patientCase3 = new PatientCase();
        $patientCase3->setPatient($patientsObjects[2]); // Third patient (Lucie Lemoine) is linked to the pathology
        $patientCase3->setCreatedAt(new \DateTimeImmutable());
        $patientCase3->setUpdatedAt(new \DateTimeImmutable());
        $patientCase3->setStartedAt(new \DateTimeImmutable('-10 days')); // Started 10 days ago
        $patientCase3->setStatus(PatientCaseStatus::ACTIVE); // Set status to ACTIVE

        $manager->persist($patientCase3);

        // Add relationships between patient cases and pathologies
        // Marie Dubois has Aponévrosite plantaire réactive
        $patientCasePathology1 = new PatientCasePathology();
        $patientCasePathology1->setPatientCase($patientCase); // Link to Marie Dubois' case
        $patientCasePathology1->setPathology($pathologiesObjects[0]); // Link to Aponévrosite plantaire réactive
        $patientCasePathology1->setCreatedAt(new \DateTimeImmutable());
        $patientCasePathology1->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($patientCasePathology1);

        // Paul Martin has Entorse LCA
        $patientCasePathology2 = new PatientCasePathology();
        $patientCasePathology2->setPatientCase($patientCase2); // Link to Paul Martin's case
        $patientCasePathology2->setPathology($pathologiesObjects[1]); // Link to Entorse LCA
        $patientCasePathology2->setCreatedAt(new \DateTimeImmutable());
        $patientCasePathology2->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($patientCasePathology2);

        // Lucie Lemoine has LMA ischios grade 0-1
        $patientCasePathology3 = new PatientCasePathology();
        $patientCasePathology3->setPatientCase($patientCase3); // Link to Lucie Lemoine's case
        $patientCasePathology3->setPathology($pathologiesObjects[2]); // Link to LMA ischios grade 0-1
        $patientCasePathology3->setCreatedAt(new \DateTimeImmutable());
        $patientCasePathology3->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($patientCasePathology3);

        // Add relationships between patient cases and physios
        // Marie Dubois is treated by Sophie Bodiguel
        $patientCasePhysio1 = new PatientCasePhysio();
        $patientCasePhysio1->setPatientCase($patientCase); // Link to Marie Dubois' case
        $patientCasePhysio1->setUser($usersObjects[1]); // Link to Sophie Bodiguel
        $patientCasePhysio1->setWorkplace($workplacesObjects[0]); // Link to Keops
        $patientCasePhysio1->setCreatedAt(new \DateTimeImmutable());
        $patientCasePhysio1->setUpdatedAt(new \DateTimeImmutable());
        $patientCasePhysio1->setStartedAt(new \DateTimeImmutable('-15 days')); // Started 15 days ago
        $patientCasePhysio1->setIsPrimary(true); // Set as primary physio

        $manager->persist($patientCasePhysio1);

        // Paul Martin is treated by Jean Dupont
        $patientCasePhysio2 = new PatientCasePhysio();
        $patientCasePhysio2->setPatientCase($patientCase2); // Link to Paul Martin's case
        $patientCasePhysio2->setUser($usersObjects[2]); // Link to Jean Dupont
        $patientCasePhysio2->setWorkplace($workplacesObjects[0]); // Link to Keops
        $patientCasePhysio2->setCreatedAt(new \DateTimeImmutable());
        $patientCasePhysio2->setUpdatedAt(new \DateTimeImmutable());
        $patientCasePhysio2->setStartedAt(new \DateTimeImmutable('-30 days')); // Started 30 days ago
        $patientCasePhysio2->setIsPrimary(true); // Set as primary physio

        $manager->persist($patientCasePhysio2);

        // Lucie Lemoine is treated by Sophie Bodiguel
        $patientCasePhysio3 = new PatientCasePhysio();
        $patientCasePhysio3->setPatientCase($patientCase3); // Link to Lucie Lemoine's case
        $patientCasePhysio3->setUser($usersObjects[1]); // Link to Sophie Bodiguel
        $patientCasePhysio3->setWorkplace($workplacesObjects[0]); // Link to Keops
        $patientCasePhysio3->setCreatedAt(new \DateTimeImmutable());
        $patientCasePhysio3->setUpdatedAt(new \DateTimeImmutable());
        $patientCasePhysio3->setStartedAt(new \DateTimeImmutable('-10 days')); // Started 10 days ago
        $patientCasePhysio3->setIsPrimary(true); // Set as primary physio

        $manager->persist($patientCasePhysio3);

        $manager->flush();
    }
}
