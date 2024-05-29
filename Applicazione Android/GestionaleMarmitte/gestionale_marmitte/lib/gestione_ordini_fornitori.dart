import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class GestioneAcquistiFornitoriPage extends StatefulWidget {
  final String ip;

  GestioneAcquistiFornitoriPage(this.ip);

  @override
  _GestioneAcquistiFornitoriPageState createState() => _GestioneAcquistiFornitoriPageState(ip);
}

class _GestioneAcquistiFornitoriPageState extends State<GestioneAcquistiFornitoriPage> {
  List<dynamic> acquisti = [];
  final String ip;

  _GestioneAcquistiFornitoriPageState(this.ip);

  @override
  void initState() {
    super.initState();
    fetchAcquisti();
  }

  Future<void> fetchAcquisti() async {
    final response = await http.get(Uri.parse('http://$ip/Server/acquisti_fornitori_read.php'));
    if (response.statusCode == 200) {
      var jsonData = json.decode(response.body);
      if (jsonData is List) {
        setState(() {
          acquisti = jsonData;
        });
      }
    } else {
      print('Errore nel caricamento dei dati');
    }
  }

  Widget _buildAcquistoCard(Map<String, dynamic> acquisto) {
    return Card(
      color: Colors.purple[50],
      elevation: 2,
      margin: const EdgeInsets.symmetric(vertical: 6, horizontal: 10),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: Colors.deepPurple,
          child: Text(
            acquisto["ID_Acquisto"].toString(),
            style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white),
          ),
        ),
        title: Text(
          "Fornitore: ${acquisto["NomeFornitore"]}",
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.deepPurple[800]),
        ),
        subtitle: Text("Data: ${acquisto["Data_Acquisto"]} - Stato: ${acquisto["Stato"]}"),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Gestione Acquisti Fornitori', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)),
        backgroundColor: Colors.deepPurple,
      ),
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topRight,
            end: Alignment.bottomLeft,
            colors: [Colors.purple, Colors.deepPurple],
          ),
        ),
        child: acquisti.isNotEmpty
            ? ListView.builder(
                itemCount: acquisti.length,
                itemBuilder: (context, index) => _buildAcquistoCard(acquisti[index]),
              )
            : Center(child: Text("Nessun acquisto disponibile")),
      ),
    );
  }
}
