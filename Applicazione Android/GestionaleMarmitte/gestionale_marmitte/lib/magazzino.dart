import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class MagazzinoPage extends StatefulWidget {
  final String ip;

  MagazzinoPage(this.ip);

  @override
  _MagazzinoPageState createState() => _MagazzinoPageState(ip);
}

class _MagazzinoPageState extends State<MagazzinoPage> {
  List<dynamic> magazzino = [];
  final String ip;

  _MagazzinoPageState(this.ip);

  @override
  void initState() {
    super.initState();
    fetchMagazzino();
  }

  Future<void> fetchMagazzino() async {
    final response = await http.get(Uri.parse('http://$ip/Server/magazzino_read.php'));
    if (response.statusCode == 200) {
      setState(() {
        magazzino = json.decode(response.body);
      });
    } else {
      print('Errore nel caricamento dei dati');
    }
  }

  Widget _buildMagazzinoCard(Map<String, dynamic> prodotto) {
    return Card(
      color: Colors.deepPurple[50], // Light purple card color for better theme integration
      elevation: 4,
      margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 10),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: Colors.deepPurple, // Deep purple color for the avatar background
          child: Text(
            prodotto["Nome"] != null && prodotto["Nome"].isNotEmpty ? prodotto["Nome"][0] : "?",
            style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white),
          ),
        ),
        title: Text(
          prodotto["Nome"] ?? "Sconosciuto",
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.deepPurple[800]),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text("ID Prodotto: ${prodotto["ID_Prodotto_Finito"]}", style: TextStyle(color: Colors.deepPurple[700])),
            Text("Quantità: ${prodotto["Quantita"]}", style: TextStyle(color: Colors.deepPurple[700])),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Gestione Magazzino',style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)),
        backgroundColor: Colors.deepPurple, // AppBar color set to match theme
      ),
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topRight,
            end: Alignment.bottomLeft,
            colors: [Colors.purple, Colors.deepPurple],
          ),
        ),
        child: magazzino.isNotEmpty
            ? ListView.builder(
                padding: const EdgeInsets.all(8.0),
                itemCount: magazzino.length,
                itemBuilder: (context, index) => _buildMagazzinoCard(magazzino[index]),
              )
            : Center(
                child: CircularProgressIndicator(),
              ),
      ),
    );
  }
}
