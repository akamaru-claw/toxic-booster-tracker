# Sicherheitskonzept — Toxic Booster Tracker

## Bedrohungsmodell

Zielgruppe sind Bitcoiner mit hohem Sicherheitsbewusstsein. Das System muss folgende Bedrohungen mitigieren:

### 1. Datenabfluss (Data Leakage)
- **Kein Tracking/Analytics** — Null Third-Party Scripts
- **Keine externen CDNs** — alle Assets self-hosted
- **Keine Google Fonts** — Systemfonts nur
- **Keine E-Mail-Erfassung** — kein Datenabgleich möglich
- **CSP Header** verhindern Inline-Script-Injection von Dritten

### 2. Account-Sicherheit
- **Password Hashing** via PHP `password_hash()` (standardmäßig bcrypt, Argon2id falls verfügbar)
- **256-bit Session Tokens** via `random_bytes(32)` — kryptografisch sicher
- **Session Expiry** nach 30 Tagen — Tokens werden serverseitig geprüft
- **Kein Password-Reset via E-Mail** — Benutzer sind selbst verantwortlich (Bitcoiner-Philosophie: Not your keys, not your coins)

### 3. Injection-Angriffe
- **Prepared Statements** in allen SQLite-Queries (kein String-Concat)
- **Input Validation** für alle API-Parameter (Typ, Länge, Range)
- **XSS-Schutz** via Content-Security-Policy + Output Encoding
- **CSRF-Schutz** durch Token-basierte Auth — kein Cookie-Based Session

### 4. Datenbank
- **WAL-Mode** für zuverlässige Parallelzugriffe ohne Locking-Probleme
- **SQLite** — kein separater DB-Server, kleinere Angriffsfläche
- **Server-side .db-Datei** — nicht über Web erreichbar (außerhalb public/)

### 5. Transport-Sicherheit
- **HTTPS Only** — Strato liefert TLS
- **HSTS** empfohlen (via .htaccess)
- **Keine sensitiven Daten in URLs** — Tokens im POST-Body, nicht in Query-Strings

## Known Limitations
- SQLite ist Single-File — bei sehr hohem Traffic könnte PostgreSQL nötig werden
- Keine Rate-Limiting auf API-Ebene (Strato hat Server-Level Limits)
- Keine 2FA (kann als Feature ergänzt werden)

## Future Improvements
- [ ] Rate Limiting auf Login-Endpunkte (Brute-Force Protection)
- [ ] Optional: 2FA via TOTP
- [ ] Optional: E-Mail-basierter Account-Recovery (opt-in)
- [ ] CSP-Header via .htaccess statt inline