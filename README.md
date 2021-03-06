Περιεχόμενα
=================
   * [Εγκατάσταση](#Εγκατάσταση)
      * [Απαιτήσεις](#Απαιτήσεις)
      * [Οδηγίες Εγκατάστασης](#Οδηγίες-Εγκατάστασης)
   * [Γενικά](#Γενικά)
      * [Το παιχνίδι Μουτζούρης](#Το-παιχνίδι-Μουτζούρης)
      * [Βασικές αρχές Εφαρμογής](#Βασικές-αρχές-Εφαρμογής)
      * [Σχεδίαση](#Σχεδίαση)
      * [Αρχιτεκτονική](#Αρχιτεκτονική)
      * [Υλοποίηση](#Υλοποίηση)
   * [API](#API)
     * [users](#users)
        * [Πιστοποίηση χρήστη](#Πιστοποίηση-χρήστη)
     * [playings](#playings)
        * [Έναρξη παιξίματος](#Έναρξη-παιξίματος)
        * [Board](#Board)
        * [Παλαιότερα αποτελέσματα](#Παλαιότερα-αποτελέσματα)
     * [players](#players)
        * [Προσθήκη Παίκτη](#Προσθήκη-Παίκτη)
        * [Πάρε Χαρτί](#Πάρε-Χαρτί)
        * [Ρίξε διπλά Χαρτιά](#Ρίξε-διπλά-Χαρτιά)
   * [Entities](#Entities)

# Demo Page

Μπορείτε να επισκευτείτε τη σελίδα: 
https://users.it.teithe.gr/~it185383/ADISE21_185383



# Εγκατάσταση

## Απαιτήσεις

* Apache2
* Mysql Server
* php

## Οδηγίες Εγκατάστασης

 * Κάντε clone το project σε κάποιον φάκελο που είναι προσβάσιμος από τον Apache Server:<br/>
  `$ git clone https://github.com/iee-ihu-gr-course1941/ADISE21_185383.git`

 * Δημιουργείστε στη MySQL τη βάση δεδομένων της εφαρμογής (***adise21_185383***) εκτελώντας το αρχείο εντολών **adise21_185383.sql** που βρισκεται στο ριζικό φάκελο του project.

  Για ευκολία, στη βάση δεδομένων και κατά την δημιουργία της, εισάγονται αυτόματα έξι έτοιμοι λογαριασμοί χρηστών με όνομα **player*N*** (όπου Ν ακέραιος από 1 μέχρι 6) και συνθηματικό το ***Ν***. Για παράδειγμα, o χρήστης* player1* έχει συνθηματικό *1*.

 * Ενημερώστε την παράμετρο **apiUrl**, στο αρχείο παραμέτρων **ui/config.php** του client της εφαρμογής, με το URL της εφαρμογής:
```
<?php

	return array(
      'apiUrl' => 'https://users.it.teithe.gr/~it185383/ADISE21_185383/'
	);
```
 * Ενημερώστε τα στοιχεία σύνδεσης με τη βάση δεδομένων στο αρχείο παραμέτρων **app/infrastructure/DB.php** του server της εφαρμογής:
```
<?php
	class DB {
    	private static $user = 'root';
    	private static $password = 'root';
    	private static $database = 'adise21_185383';
		private static $host = '';
    	private static $sock = '/home/student/it/2018/it185383/mysql/run/mysql.sock'; 
		.........................................................................
		.........................................................................
```

# Γενικά

Η εφαρμογή προσφέρει, σε μία ομάδα από 2-6 άτομα, την δυνατότητα να παίξουν το παιχνίδι *μουτζούρης*.

## Το παιχνίδι Μουτζούρης
Ο μουτζούρης παίζεται ως εξής: 

### Στόχος
Ο στόχος του παιχνιδιού είναι να μείνεις χωρίς φύλλα στο χέρι. Αυτός που θα μείνει με ένα ή περισσότερα φύλλα είναι ο χαμένος.

### Προετοιμασία
Αρχικά, αφαιρούνται από την τράπουλα όλες οι φιγούρες και μένει μόνο ο Ρήγας Μπαστούνι.

### Διαδικασία παιχνιδιού
Αφού ανακατέψουμε καλά, μοιράζουμε όλη την τράπουλα στους παίχτες έτσι ώστε όλοι να έχουν των ίδιο αριθμό φύλλων (ή + - 1). Κάθε παίχτης αφαιρεί από τα φύλλα που έχει στα χέρια του τα ζευγάρια, δηλαδή, 2 Άσσους 2 δυάρια 2 τριάρια κ.τ.λ. Τα υπόλοιπα τα κρατάμε στο χέρι σαν βεντάλια έτσι ώστε να μπορεί ο άλλος παίχτης να διαλέξει, χωρίς να τα βλέπει, ένα από αυτά. Ο πρώτος παίχτης τραβάει ένα φύλλο από αυτόν που κάθετε στα αριστερά του, αν κάνει ζευγάρι το νέο χαρτί με κάποια από τα δικά του τότε τα ρίχνει, αλλιώς τα κρατάει και συνεχίζει ο επομένως που είναι στα δεξιά του. Όποιος ζευγαρώσει όλα τα φύλλα του βγαίνει από το παιχνίδι. Όποιος μείνει τελευταίος με τον Ρήγα Μπαστούνι (τον Μουτζούρη) στο χέρι του είναι ο χαμένος, και οι υπόλοιποι παίχτες αποφασίζουν την ποινή του.

## Βασικές αρχές Εφαρμογής
- Για να παίξει κάποιος πρέπει πρώτα να πιστοποιηθεί. Η εφαρμογή προσφέρει 6 προεγκατεστημένους λογαριασμούς χρηστών (*player1*, ..., *player6*).
- Κάθε παίξιμο παραμένει ενεργό μέχρι να προκύψει νικητής.
- Υπάρχει το πολύ ένα ενεργό παίξιμο κάθε στιγμή (δηλαδή, η εφαρμογή προσφέρει ένα μόνο board).
- Τα αποτελέσματα κάθε παιξίματος που ολοκληρώνεται αποθηκεύονται για ιστορικούς λόγους (scoreboard).

## Σχεδίαση
Η εφαρμογή αποτελείται από δύο βασικά, εντελώς ανεξάρτητα μεταξύ τους, μέρη: 
- **app** (εκτέλεση παιχνιδιού και διαχείρισης βάσης δεδομένων)
- **ui** (διεπαφή χρήστη)

Επιπρόσθετα, η εφαρμογή υποστηρίζεται από μία βάση δεδομένων για την αποθήκευση: i) των στοιχείων των χρηστών-παικτών και ii) την κατάσταση των παιξιμάτων.

> Η διαχείριση της εφαρμογής γίνεται μόνο από το *app*. To *ui* δεν έχει φυσικά πρόσβαση στη βάση δεδομένων.Η διαχείριση της εφαρμογής γίνεται μόνο από το *app*. To *ui* δεν έχει φυσικά πρόσβαση στη βάση δεδομένων.

## Αρχιτεκτονική
Η εφαρμογή στη *RESTful* αρχιτεκτονική καθώς, το *ui* μέρος επικοινωνεί με το *app* με *REST API* κλήσεις που βασίζονται στην ανταλλαγή JSON αντικειμένων.

Πιο αναλυτικά, η γενική δομή της εφαρμογής είναι η ακόλουθη:

index.html

**\ui**   

*ιστοσελίδες διεπαφής χρήστη*

**\ui\actions**

*εκτέλεση πράξεων χρήστη*

**\app**

**\app\api**

*REST API*

**\app\models**

*μοντελοποίηση οντοτήτων βάσης δεδομένων*

**\app\infrastructure**

*βοηθητικές λειτουργίες*

## Υλοποίηση
Η εφαμογή αναπτύχθηκε εξ' ολοκλήρου ως ατομικό έργο.

# API
> Κάθε μέθοδος του API, εκτός από αυτή της "Πιστοποίησης Χρήστη", εκτελείται μόνο σε εξουσιοδοτημένες κλήσεις (δηλαδή, αυτές που περιέχουν στο header τους ένα *token* που παράγεται κατά την πιστοποίηση χρήστη). 
Έτσι, κάθε μέθοδος δέχεται (και έχει, αυτόματα, στην διάθεσή της) το Id του τρέχοντος χρήστη.

Όλες οι μέθοδοι παρουσιάζονται παρακάτω.

## users

### Πιστοποίηση χρήστη
```
POST /users/checkPwd
```
Ελέγχει την ορθότητα των στοιχείων σύνδεσης ενός χρήστη (όνομα και συνθηματικό) και επιστρέφει : i) τα στοιχεία του χρήστη και ii) το Id του χρήστη, ως *token*, πάνω στο οποίο στηρίζεται η επικοινωνία του ui με το app, καθ' όλη την διάρκεια του παιξίματος.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `username`        | Το username που πληκτρολόγησε ο χρήστης| yes        |
| `password`           | To συνθηματικό που πληκτρολόγησε ο χρήστης| yes        |

## playings

### Έναρξη παιξίματος
```
POST /playings/start
```

Ξεκινά ένα νέο παίξιμο, αν δεν υπάρχει κάποιο ενεργό ήδη, και ορίζει τον τρέχοντα χρήστη ως πρώτο παίκτη.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `player_cnt`        | Το συνολικό πλήθος παικτών που θα έχει το νέο παίξιμο| yes        |

### Board
```
GET /playings/board
```

Επιστρέφει το τρέχον περιεχόμενο του *board* με βάση τον τρέχοντα χρήστη και τη τρέχουσα φάση του ενεργού παιξίματος.

### Παλαιότερα αποτελέσματα
```
GET /playings/scoreBoard
```

Επιστρέφει τα αποτελέσματα των παλαιότερων παιξιμάτων.

##players

### Προσθήκη Παίκτη
```
POST /players/add
```

Προσθέτει τον τρεχοντα χρήστη, ως νέο παίκτη, στο ενεργό παίξιμο.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `playing_id`        | Το παίξιμο στο οποίο προστίθεται, ως παίκτης, ο χρήστης| yes        |

### Πάρε Χαρτί
```
POST /players/pick
```

Δίνει στον τρέχοντα χρήστη-παίκτη ένα χαρτί από τη βεντάλια του προηγούμενου (κατά σειρά ενταξης στο παιχνίδι) παίκτη.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `card_to_pick`        | Το χαρτί (1 - 41) που δίνεται στον παίκτη| yes        |

### Ρίξε διπλά Χαρτιά
```
POST /players/throw
```

"Ρίχνει" τα χαρτιά που επέλεξε ο τρέχοντας χρήστης-παίκτης.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `cards_to_throw`        | Λίστα διπλών χαρτιών| yes        |

# Entities

## user

Περιέχει τoυς Χρήστες που μπορούν να παίξουν.


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `id`               | Id Χρήστη                                 | ΙΝΤ                              |
| `name`                | Όνομα Χρήστη | String |
| `password`                | Συνθηματικό Χρήστη | String |

## playing

Περιέχει την κατάσταση του ενεργού και των παλαιότερων παιξιμάτων.


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `id`               | Id Παιξίματος                                 | ΙΝΤ (auto increment)                             |
| `active`                | Ενεργό Παίξιμο | 0.Όχι, 1.Ναι |
| `phase`                | Φάση Παιξίματος | 0.Αρχική, 1.Ένταξη παικτών, 2.Αρχική απόρριψη διπλών, 3.Παιχνίδι, 4.Τερματισμός |
| `player_cnt`                | Πλήθος Παικτών | ΙΝΤ |

## card

Ο πίνακας card περιέχει τα 41 χαρτιά της τράπουλας που χρησιμοποιούνται στο Μουτζούρη.


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `id`                      | Id              |   ΙΝΤ                              |
| `figure`                      | Φιγούρα               |'A', '2'....'10', 'K'                                |
| `symbol`                | Σύμβολο                      |'Κούπα', 'Σπαθί', 'Καρό', 'Μπαστούνι'                             |
| `player_id`            |   Id του Παίκτη που κατέχει το χαρτί                       |  ΙΝΤ                    |
| `playing_id`                  |  Id ενεργού Παιξίματος          |   ΙΝΤ     |
| `player_seqno`                  | Σειρά - του Χαρτιού - στη βεντάλια του Παίκτη |  ΙΝΤ           |

## player

Περιέχει τους παίκτες που έχουν συμμετάσχει στα διάφορα παιξίματα.


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `playing_id`               | Id Παιξίματος                                 | ΙΝΤ  (auto increment)                            |
| `id`            | Id αντίστοιχου Χρήστη                | ΙΝΤ                             |
| `playing_iscurrent`                | Ενεργό Παίξιμο | 0.Όχι, 1.Ναι |
| `state`                | Τρέχουσα κατάσταση Παίκτη | 1.Ένταξη, 2. Aπόρριψη διπλών, 3. Επιλογή χαρτιού |
| `final_card_cnt`                | Πλήθος Χαρτιών που έμειναν στα χέρια του Παίκτη κατά την ολοκλήρωση του Παιξίματος | ΙΝΤ |

