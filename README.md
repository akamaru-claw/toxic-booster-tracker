# Toxic Booster – Sammelkarten-Tracker

Ein Open-Source Sammelkarten-Tracker für die **Einundzwanzig Zitadelle MX12 Toxic Booster Genesis Edition** Sammelkarten.

> 21 Karten × 210 Stück — Behalte den Überblick über deine Sammlung und finde Tauschpartner.

## 🃏 Über die Karten

Die **Toxic Booster – Genesis Edition** ist das offizielle Sammelkartenspiel der deutschen Bitcoin-Community, kreiert vom Bitcoin-Künstler **MX12** ([mx12.art](https://mx12.art)) und veröffentlicht auf der **Bitcoin Zitadelle 2025** in Heldrungen.

- **21 verschiedene Karten**, durchnummeriert von 1 bis 21
- **210 Exemplare** pro Karte
- Künstler: [MX12](https://mx12.art) — *„Shaped by the harsh winds of Latvia and the freedom of the Internet"*
- Community: [Einundzwanzig](https://einundzwanzig.shop)

## 🔒 Sicherheit & Privatsphäre

Dieses Projekt richtet sich an Bitcoiner, die IT-Sicherheit und Privatsphäre schätzen:

- **Kein Tracking**, keine Analytics, kein Google Fonts
- **Keine externen CDNs** — alle Ressourcen sind self-hosted
- **Password Hashing** mit `password_hash()` (bcrypt/argon2)
- **Session Tokens** — kryptografisch sicher (256-bit), 30 Tage gültig
- **Keine E-Mail-Pflicht** — kein persönlicher Datenabgleich möglich
- **SQLite Backend** — leichtgewichtig, keine Datenbank-Server-Abhängigkeit
- **CSP-Headers** (Content Security Policy) gegen XSS
- **Input Validation** serverseitig für alle Endpunkte
- **WAL-Mode** für zuverlässige Parallelzugriffe

## 🚀 Live

**[tracker.ml-bets.com](https://tracker.ml-bets.com)** (oder Pfad auf ml-bets.com)

## 📁 Projektstruktur

```
├── public/               # Webroot (Deploy-Ordner)
│   ├── index.html        # Frontend App
│   ├── auth_api.php      # Account-System API
│   ├── cards_api.php     # Karten-Daten API
│   └── assets/           # Bilder, Icons, etc.
├── docs/                 # Dokumentation
│   ├── SECURITY.md       # Sicherheitskonzept
│   ├── API.md            # API-Dokumentation
│   └── CARDS.md          # Karten-Referenz
├── deploy.sh             # Deploy-Script für Strato SFTP
├── README.md             # Dieses File
└── .gitignore
```

## 🛠 Entwicklung

```bash
# Lokal testen (PHP Built-in Server)
cd public && php -S localhost:8080

# Deploy auf Strato
bash deploy.sh
```

## 🤝 Mitmachen

Pull Requests Willkommen! Bitte:

1. Fork erstellen
2. Feature-Branch (`git checkout -b feature/xxx`)
3. Commit (`git commit -m "Beschreibung"`)
4. Push (`git push origin feature/xxx`)
5. Pull Request öffnen

## 📜 Lizenz

MIT — Open Source, wie es sich für Bitcoin-Software gehört.

---

*Toxic Booster Genesis Edition © MX12 — Dieses Tracker-Projekt ist ein inoffizielles Community-Tool.*