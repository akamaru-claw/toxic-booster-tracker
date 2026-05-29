# API-Dokumentation — Toxic Booster Tracker

## Basis-URL
Alle Endpunkte sind relativ zum Installationspfad.

## Authentifizierung

Alle `cards_api.php`-Aufrufe benötigen ein gültiges `token` im Request-Body.

---

## auth_api.php

### `register`
Neuen Account erstellen.

**Request:**
```json
{ "action": "register", "username": "satoshi", "password": "hodl2025" }
```

**Response OK:**
```json
{ "ok": true, "token": "<256-bit-hex>", "username": "satoshi", "user_id": 1 }
```

**Validierung:**
- Username: 3-20 Zeichen, `[a-zA-Z0-9_äöüß]`
- Passwort: mindestens 4 Zeichen

---

### `login`
Bestehenden Account einloggen.

**Request:**
```json
{ "action": "login", "username": "satoshi", "password": "hodl2025" }
```

**Response OK:**
```json
{ "ok": true, "token": "<256-bit-hex>", "username": "satoshi", "user_id": 1 }
```

---

### `logout`
Session beenden.

**Request:**
```json
{ "action": "logout", "token": "<token>" }
```

---

### `verify`
Session-Gültigkeit prüfen.

**Request:**
```json
{ "action": "verify", "token": "<token>" }
```

**Response OK:**
```json
{ "ok": true, "user_id": 1, "username": "satoshi" }
```

---

## cards_api.php

Alle Endpunkte benötigen Authentifizierung via `token`.

### `load`
Kartensammlung laden.

**Request:**
```json
{ "action": "load", "token": "<token>" }
```

**Response:**
```json
{ "ok": true, "cards": [0, 0, 0, ...], "username": "satoshi" }
```

`cards` ist ein Array der Länge 21, Index 0 = Karte #1, Wert = Anzahl (0-210).

---

### `save`
Kartensammlung speichern.

**Request:**
```json
{ "action": "save", "token": "<token>", "cards": [5, 2, 0, ...] }
```

**Validierung:**
- Array muss genau 21 Elemente haben
- Jeder Wert: Integer, 0-210