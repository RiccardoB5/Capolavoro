# Documentazione dell'Applicazione Gestione Ordini Fornitori

## Panoramica

L'applicazione Gestione Ordini Fornitori è progettata per automatizzare e semplificare la gestione degli ordini e delle scorte di magazzino per i fornitori. È costruita utilizzando Flutter per il frontend e PHP per il backend, offrendo una soluzione cross-platform reattiva e accessibile da qualsiasi dispositivo.

## Struttura dei File

L'applicazione è organizzata nei seguenti file principali:

- `main.dart`: Punto di ingresso dell'applicazione. Configura il tema, la localizzazione e la navigazione.
- `dashboard.dart`: Mostra le metriche chiave e le statistiche in una vista aggregata.
- `anagrafica_prodotti.dart`: Permette la gestione dell'anagrafica dei prodotti.
- `magazzino.dart`: Gestisce le informazioni relative alle scorte di magazzino.
- `gestione_ordini_fornitori.dart`: Consente di gestire gli ordini verso i fornitori.

## Funzionalità

### Dashboard
- Visualizzazione delle statistiche di vendita e acquisto.
- Grafici di performance per una rapida analisi visiva.

### Gestione Anagrafica Prodotti
- Aggiunta, modifica e cancellazione dei prodotti dal sistema.
- Ricerca e visualizzazione dettagliata dei prodotti.

### Gestione Magazzino
- Monitoraggio delle scorte attuali.
- Aggiornamento delle quantità di magazzino basato sulle operazioni di acquisto e vendita.

### Gestione Ordini Fornitori
- Creazione di nuovi ordini ai fornitori.
- Aggiornamento dello stato degli ordini esistenti.
- Visualizzazione storico degli ordini.

## Tecnologie Utilizzate

- **Frontend**: Flutter & Dart
- **Backend**: PHP
- **Database**: MySQL

## Configurazione e Installazione

1. **Ambiente Server**:
   - Configura un server PHP con accesso a MySQL.
   - Assicurati che il server supporti HTTPS per la sicurezza delle comunicazioni.

2. **Database**:
   - Crea il database utilizzando lo schema fornito nel file `schema.sql`.
   - Configura le credenziali di accesso nel file `database.php`.

3. **Installazione Flutter**:
   - Clona il repository nel tuo ambiente locale.
   - Esegui `flutter pub get` per installare le dipendenze necessarie.

4. **Avvio dell'applicazione**:
   - Esegui l'applicazione Flutter tramite l'IDE o la linea di comando con `flutter run`.

## Best Practices

- Mantieni una chiara separazione tra logica di business e interfaccia utente.
- Utilizza commenti descrittivi nel codice per facilitare la manutenzione e l'aggiornamento.
- Implementa test automatici per garantire la stabilità delle funzionalità principali.

