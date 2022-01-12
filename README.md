Περιεχόμενα
=================
   * [Εγκατάσταση](#Εγκατάσταση)
      * [Απαιτήσεις](#Απαιτήσεις)
      * [Οδηγίες Εγκατάστασης](#Οδηγίες-Εγκατάστασης)
   * [Γενικά](#Γενικά)
      * [Το παιχνίδι Μουτζούρης](#Το-παιχνίδι-Μουτζούρης)
      * [Σχεδίαση-Αρχιτεκτονική](#Σχεδίαση-Αρχιτεκτονική)
      * [Συντελεστές](#Συντελεστές)
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

 * Δημιουργείστε στη MySQL τη βάση δεδομένων της εφαρμογής (***adise21_185383***) εκτελώντας το αρχείο εντολών **arxeio.sql** που βρισκεται στο ριζικό φάκελο του project.

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
- Για να παίξει κάποιος πρέπει πρώτα να πιστοποιηθεί


##Σχεδίαση-Αρχιτεκτονική
Η εφαρμογή αποτελείται από δύο βασικά, εντελώς ανεξάρτητα μεταξύ τους, μέρη: 
- **ui** (διεπαφή χρήστη)
- **app** (εκτέλεση παιχνιδιού και διαχείρισης βάσης δεδομένων)

Βασίζεται στη *RESTful* αρχιτεκτονική καθώς, το *ui* μέρος επικοινωνεί με το *app* με *REST API* κλήσεις που βασίζονται στην ανταλλαγή JSON αντικειμένων.

Πιο αναλυτικά, η γενική δομή της εφαρμογής είναι η ακόλουθη:
**\**
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


## Συντελεστές
Η εφαμογή αναπτύχθηκε εξ' ολοκλήρου ως ατομικό έργο.

# API

## users

### Πιστοποίηση χρήστη
```
POST /users/checkPwd
```
Ελέγχει την ορθότητα των στοιχείων σύνδεσης του χρήστη (όνομα και συνθηματικό) και επιστρέφει : i) τα στοιχεία του χρήστη και ii) το *token* στο οποίο θα στηρίζεται η επικοινωνία του ui με το app, καθ' όλη την διάρκεια του παιξίματος.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `username`        | Το username που πληκτρολόγησε ο χρήστης| yes        |
| `password`           | To συνθηματικό που πληκτρολόγησε ο χρήστης| yes        |

##playings

###Έναρξη παιξίματος
```
POST /playings/start
```

Ξεκινά ένα νέο παίξιμο και θεωρεί τον τρέχοντα χρήστη ως πρώτο παίκτη.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `player_cnt`        | Το συνολικό πλήθος παικτών που θα έχει το νέο παίξιμο| yes        |

### Board
```
GET /playings/board
```

Επιστρέφει το τρέχον περιεχόμενο του *board* με βάση τον τρέχοντα χρήστη και τη τρέχουσα φάση του παιξίματος.

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

Προσθέτει ένα χρήστη, ως νέο παίκτη, σε ένα παίξιμο.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `playing_id`        | Το παίξιμο στο οποίο προστίθεται, ως παίκτης, ο χρήστης| yes        |

### Πάρε Χαρτί
```
POST /players/pick
```

Δίνει σε ένα παίκτη ένα χαρτί από τη βεντάλια του προηγούμενου παίκτη.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `card_to_pick`        | Το χαρτί (1 - 40) που δίνεται στον παίκτη| yes        |

### Ρίξε διπλά Χαρτιά
```
POST /players/throw
```

Ρίχνει τα χαρτιά ενός παίκτη.

Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `cards_to_throw`        | Λίστα διπλών χαρτιών| yes        |
