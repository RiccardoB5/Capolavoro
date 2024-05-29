import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class AnagraficaProdottiPage extends StatefulWidget {
  final String ip;

  AnagraficaProdottiPage(this.ip);

  @override
  _AnagraficaProdottiPageState createState() => _AnagraficaProdottiPageState(ip);
}

class _AnagraficaProdottiPageState extends State<AnagraficaProdottiPage> {
  List<dynamic> prodotti = [];
  final String ip;

  _AnagraficaProdottiPageState(this.ip);

  @override
  void initState() {
    super.initState();
    fetchProdotti();
  }

  Future<void> fetchProdotti() async {
    final response = await http.get(Uri.parse('http://$ip/Server/anagrafica_prodotti_read.php'));
    if (response.statusCode == 200) {
      setState(() {
        prodotti = json.decode(response.body);
      });
    } else {
      print('Errore nel caricamento dei dati');
    }
  }

  Widget _buildProductCard(Map<String, dynamic> prodotto) {
    return Card(
      color: Colors.deepPurple[50],
      elevation: 4,
      margin: const EdgeInsets.symmetric(vertical: 6, horizontal: 10),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: Colors.deepPurple,
          child: Text(
            prodotto["Nome"] != null && prodotto["Nome"].isNotEmpty ? prodotto["Nome"][0] : "?",
            style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white),
          ),
        ),
        title: Text(
          prodotto["Nome"] ?? "Sconosciuto",
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.deepPurple[800]),
        ),
        subtitle: Text("ID Prodotto: ${prodotto["ID_Prodotto"]} - Tipo: ${prodotto["Tipo"]}"),
        trailing: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            IconButton(
              icon: Icon(Icons.edit, color: Colors.orange),
              onPressed: () => _mostraDialogModificaProdotto(context, prodotto),
            ),
            IconButton(
              icon: Icon(Icons.delete, color: Colors.red),
              onPressed: () => deleteProdotto(int.parse(prodotto["ID_Prodotto"].toString())),
            ),
          ],
        ),
      ),
    );
  }

  void _mostraDialogModificaProdotto(BuildContext context, Map<String, dynamic> prodotto) {
    final TextEditingController nomeController = TextEditingController(text: prodotto['Nome']);
    final TextEditingController descrizioneController = TextEditingController(text: prodotto['Descrizione']);
    String tipoSelezionato = prodotto['Tipo'];

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Modifica Prodotto'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: nomeController,
                  decoration: InputDecoration(labelText: 'Nome'),
                ),
                TextField(
                  controller: descrizioneController,
                  decoration: InputDecoration(labelText: 'Descrizione'),
                ),
                DropdownButton<String>(
                  isExpanded: true,
                  value: tipoSelezionato,
                  onChanged: (String? newValue) {
                    setState(() {
                      tipoSelezionato = newValue ?? tipoSelezionato;
                    });
                  },
                  items: <String>['Marmitta', 'Componente']
                      .map<DropdownMenuItem<String>>((String value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Text(value),
                    );
                  }).toList(),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context). pop(),
              child: Text('Annulla'),
            ),
            ElevatedButton(
              onPressed: () {
                updateProdotto(
                  int.parse(prodotto['ID_Prodotto'].toString()),
                  nomeController.text,
                  descrizioneController.text,
                  tipoSelezionato,
                );
                Navigator.of(context).pop();
              },
              child: Text('Salva Modifiche'),
            ),
          ],
        );
      },
    );
  }

  Future<void> updateProdotto(int id, String nome, String descrizione, String tipo) async {
    final response = await http.put(
      Uri.parse('http://$ip/Server/anagrafica_prodotti_update.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'ID_Prodotto': id, 'Nome': nome, 'Descrizione': descrizione, 'Tipo': tipo}),
    );

    if (response.statusCode == 200) {
      fetchProdotti();
    } else {
      print('Errore durante l aggiornamento del prodotto');
      print('Risposta del server: ${response.body}');
    }
  }

  Future<void> deleteProdotto(int id) async {
    final response = await http.delete(
      Uri.parse('http://$ip/Server/anagrafica_prodotti_delete.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'ID_Prodotto': id}),
    );

    if (response.statusCode == 204) {
      fetchProdotti();
    } else {
      print('Errore durante l\'eliminazione del prodotto');
      print('Risposta del server: ${response.body}');
    }
  }

  Future<void> createProdotto(String nome, String descrizione, String tipo) async {
    final response = await http.post(
      Uri.parse('http://$ip/Server/anagrafica_prodotti_create.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'Nome': nome, 'Descrizione': descrizione, 'Tipo': tipo}),
    );

    if (response.statusCode == 201) {
      fetchProdotti();  // Refresh the list after adding a product
    } else {
      print('Errore durante la creazione del prodotto');
      print('Risposta del server: ${response.body}');
    }
  }

   void _mostraDialogAggiungiProdotto() {
    final TextEditingController nomeController = TextEditingController();
    final TextEditingController descrizioneController = TextEditingController();
    String tipoSelezionato = 'Componente';  // Default value

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Aggiungi Prodotto'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: nomeController,
                  decoration: InputDecoration(labelText: 'Nome'),
                ),
                TextField(
                  controller: descrizioneController,
                  decoration: InputDecoration(labelText: 'Descrizione'),
                ),
                DropdownButton<String>(
                  isExpanded: true,
                  value: tipoSelezionato,
                  onChanged: (String? newValue) {
                    setState(() {
                      tipoSelezionato = newValue!;
                    });
                  },
                  items: <String>['Marmitta', 'Componente']
                      .map<DropdownMenuItem<String>>((String value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Text(value),
                    );
                  }).toList(),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: Text('Annulla'),
            ),
            ElevatedButton(
              onPressed: () {
                createProdotto(nomeController.text, descrizioneController.text, tipoSelezionato);
                Navigator.of(context).pop();
              },
              child: Text('Aggiungi'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Anagrafica Prodotti', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)),
        backgroundColor: Colors.deepPurple,
        actions: <Widget>[
          IconButton(
            icon: Icon(Icons.add),color: Colors.white,
            onPressed: _mostraDialogAggiungiProdotto,
          )
        ],
      ),
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topRight,
            end: Alignment.bottomLeft,
            colors: [Colors.purple, Colors.deepPurple],
          ),
        ),
        child: prodotti.isNotEmpty
            ? ListView.builder(
                itemCount: prodotti.length,
                itemBuilder: (context, index) => _buildProductCard(prodotti[index]),
              )
            : Center(child: CircularProgressIndicator()),
      ),
    );
  }
}
