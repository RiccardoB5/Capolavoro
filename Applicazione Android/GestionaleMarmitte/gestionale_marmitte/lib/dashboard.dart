import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class DashboardPage extends StatefulWidget {
  final String ip;

  DashboardPage(this.ip);

  @override
  _DashboardPageState createState() => _DashboardPageState(ip);
}

class _DashboardPageState extends State<DashboardPage> {
  final String ip;
  double venditeTotali = 0.0;
  double acquistiTotali = 0.0;
  int prodottiMagazzino = 0;
  int componentiMagazzino = 0;

  _DashboardPageState(this.ip);

  @override
  void initState() {
    super.initState();
    fetchDashboard();
  }

  Future<void> fetchDashboard() async {
    final response = await http.get(Uri.parse('http://$ip/Server/dashboard.php'));
    if (response.statusCode == 200) {
      try {
        final data = json.decode(response.body);
        setState(() {
          venditeTotali = (data["vendite_totali"] as num).toDouble();
          acquistiTotali = (data["acquisti_totali"] as num).toDouble();
          prodottiMagazzino = data["prodotti_magazzino"] as int;
          componentiMagazzino = data["componenti_magazzino"] as int;
        });
      } catch (e) {
        print("Errore nel parsing del JSON: $e");
        print("Risposta: ${response.body}");
      }
    } else {
      print('Errore nel caricamento dei dati del dashboard: ${response.body}');
    }
  }

  Widget _buildDashboardCard(String title, String value, IconData icon, Color? cardColor) {
    Color backgroundColor = cardColor ?? Colors.deepPurple;  // Fallback to a default color if null
    return Card(
      color: Colors.deepPurple[50], // Light purple shade for the card
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: backgroundColor, // Ensure a non-null color is used here
          child: Icon(icon, color: Colors.white),
        ),
        title: Text(
          title,
          style: TextStyle(fontWeight: FontWeight.bold, color: Colors.deepPurple),
        ),
        subtitle: Text(
          value,
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.deepPurple[800]),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Dashboard Amministrativo', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)),
        centerTitle: true,
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
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            _buildDashboardCard(
              'Vendite Totali',
              '€${venditeTotali.toStringAsFixed(2)}',
              Icons.show_chart,
              Colors.greenAccent[400], // Bright color for financial growth
            ),
            SizedBox(height: 10),
            _buildDashboardCard(
              'Acquisti Totali',
              '€${acquistiTotali.toStringAsFixed(2)}',
              Icons.shopping_cart,
              Colors.blueAccent[400], // Cool blue for expenses
            ),
            SizedBox(height: 10),
            _buildDashboardCard(
              'Prodotti in Magazzino',
              '$prodottiMagazzino',
              Icons.inventory,
              Colors.orangeAccent[400], // Orange for inventory
            ),
            SizedBox(height: 10),
            _buildDashboardCard(
              'Componenti in Magazzino',
              '$componentiMagazzino',
              Icons.build,
              Colors.purpleAccent[400], // Matching purple for components
            ),
          ],
        ),
      ),
    );
  }
}
