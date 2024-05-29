import 'package:flutter/material.dart';
import 'dashboard.dart';
import 'magazzino.dart';
import 'anagrafica_prodotti.dart';
import 'gestione_ordini_fornitori.dart'; 

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Fabbrica Marmitte',
      theme: ThemeData(
        primaryColor: Colors.deepPurple,
        hintColor: Colors.purpleAccent,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: HomePage(),
    );
  }
}

class HomePage extends StatelessWidget {
  final TextEditingController ipController = TextEditingController();

  Widget _buildFeatureButton(String title, IconData icon, Function(BuildContext, String) navigateToPage, BuildContext context) {
    return ElevatedButton.icon(
      icon: Icon(icon, size: 24),
      label: Text(title),
      onPressed: () {
        String ip = ipController.text;
        if (isValidIP(ip)) {
          navigateToPage(context, ip);
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Per favore, inserisci un indirizzo IP valido.'),
              backgroundColor: Colors.red,
            ),
          );
        }
      },
      style: ElevatedButton.styleFrom(
        foregroundColor: Colors.white, backgroundColor: Colors.deepPurple,
        textStyle: TextStyle(fontWeight: FontWeight.bold),
        padding: EdgeInsets.symmetric(vertical: 12.0),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
  }

  bool isValidIP(String ip) {
    RegExp regex = RegExp(r'^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$');
    return regex.hasMatch(ip);
  }

  @override
  Widget build(BuildContext context) {
    List<Widget> buttons = [
      _buildFeatureButton(
        'Vai alla Pagina Magazzino',
        Icons.store_mall_directory,
        (ctx, ip) => Navigator.push(ctx, MaterialPageRoute(builder: (_) => MagazzinoPage(ip))),
        context,
      ),
      _buildFeatureButton(
        'Vai alla Pagina Anagrafica Prodotti',
        Icons.list_alt,
        (ctx, ip) => Navigator.push(ctx, MaterialPageRoute(builder: (_) => AnagraficaProdottiPage(ip))),
        context,
      ),
      _buildFeatureButton(
        'Vai alla Pagina Dashboard',
        Icons.dashboard,
        (ctx, ip) => Navigator.push(ctx, MaterialPageRoute(builder: (_) => DashboardPage(ip))),
        context,
      ),
      _buildFeatureButton(
        'Gestione Ordini Fornitori',
        Icons.assignment,
        (ctx, ip) => Navigator.push(ctx, MaterialPageRoute(builder: (_) => GestioneAcquistiFornitoriPage(ip))),
        context,
      ),
    ];

    return Scaffold(
      appBar: AppBar(
        title: Text('Fabbrica Marmitte Management', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white)), 
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
        padding: EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text(
              'Inserisci l\'indirizzo IP del server',
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
            ),
            SizedBox(height: 16),
            TextField(
              controller: ipController,
              decoration: InputDecoration(
                labelText: 'Indirizzo IP',
                labelStyle: TextStyle(color: Colors.white),
                enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.purpleAccent),
                  borderRadius: BorderRadius.circular(10),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.white),
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              keyboardType: TextInputType.number,
              style: TextStyle(color: Colors.white),
            ),
            SizedBox(height: 20),
            Expanded(
              child: GridView.count(
                crossAxisCount: 2,
                childAspectRatio: 3,
                crossAxisSpacing: 10,
                mainAxisSpacing: 10,
                children: buttons,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
