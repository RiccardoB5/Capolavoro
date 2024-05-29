// cliente_page.dart
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class ClientePage extends StatefulWidget {
  @override
  _ClientePageState createState() => _ClientePageState();
}

class _ClientePageState extends State<ClientePage> {
  List<dynamic> clienti = [];

  @override
  void initState() {
    super.initState();
    fetchClienti();
  }

  Future<void> fetchClienti() async {
    final response = await http.get(Uri.parse('http://localhost/api_request.php/anagrafica_clienti.php'));
    if (response.statusCode == 200) {
      setState(() {
        clienti = json.decode(response.body);
      });
    } else {
      print('Errore nel caricamento dei dati');
    }
  }

  Future<void> createCliente(Map<String, String> data) async {
    final response = await http.post(
      Uri.parse('http://localhost/api_request.php/anagrafica_clienti.php'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode(data),
    );
    if (response.statusCode == 201) {
      fetchClienti();
    } else {
      print('Errore nella creazione del cliente');
    }
  }

  Future<void> updateCliente(int id, Map<String, String> data) async {
    final response = await http.put(
      Uri.parse('http://localhost/api_request.php/anagrafica_clienti.php'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({...data, 'ID_Cliente': id}),
    );
    if (response.statusCode == 200) {
      fetchClienti();
    } else {
      print('Errore nell\'aggiornamento del cliente');
    }
  }

  Future<void> deleteCliente(int id) async {
    final response = await http.delete(
      Uri.parse('http://localhost/api_request.php/anagrafica_clienti.php/$id'),
    );
    if (response.statusCode == 204) {
      fetchClienti();
    } else {
      print('Errore nella cancellazione del cliente');
    }
  }

  void showClienteDialog({Map<String, String>? initialData, required Function(Map<String, String>) onSave}) {
    final TextEditingController nomeController = TextEditingController(text: initialData?['Nome'] ?? '');
    final TextEditingController partitaIVAController = TextEditingController(text: initialData?['Partita_IVA'] ?? '');
    final TextEditingController telefonoController = TextEditingController(text: initialData?['Telefono'] ?? '');
    final TextEditingController emailController = TextEditingController(text: initialData?['Email'] ?? '');
    final TextEditingController passwordController = TextEditingController(text: initialData?['Password'] ?? '');

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Dettagli Cliente'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: nomeController,
                decoration: InputDecoration(labelText: 'Nome'),
              ),
              TextField(
                controller: partitaIVAController,
                decoration: InputDecoration(labelText: 'Partita IVA'),
              ),
              TextField(
                controller: telefonoController,
                decoration: InputDecoration(labelText: 'Telefono'),
              ),
              TextField(
                controller: emailController,
                decoration: InputDecoration(labelText: 'Email'),
              ),
              TextField(
                controller: passwordController,
                decoration: InputDecoration(labelText: 'Password'),
                obscureText: true,
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text('Annulla'),
            ),
            ElevatedButton(
              onPressed: () {
                onSave({
                  'Nome': nomeController.text,
                  'Partita_IVA': partitaIVAController.text,
                  'Telefono': telefonoController.text,
                  'Email': emailController.text,
                  'Password': passwordController.text,
                });
                Navigator.of(context).pop();
              },
              child: Text('Salva'),
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
        title: Text('Gestione Clienti'),
      ),
      body: ListView.builder(
        itemCount: clienti.length,
        itemBuilder: (context, index) {
          final cliente = clienti[index];
          return ListTile(
            title: Text(cliente['Nome']),
            subtitle: Text('Partita IVA: ${cliente['Partita_IVA']}'),
            trailing: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                IconButton(
                  icon: Icon(Icons.edit),
                  onPressed: () {
                    showClienteDialog(
                      initialData: {
                        'Nome': cliente['Nome'],
                        'Partita_IVA': cliente['Partita_IVA'],
                        'Telefono': cliente['Telefono'],
                        'Email': cliente['Email'],
                        'Password': cliente['Password'],
                      },
                      onSave: (data) => updateCliente(cliente['ID_Cliente'], data),
                    );
                  },
                ),
                IconButton(
                  icon: Icon(Icons.delete),
                  onPressed: () => deleteCliente(cliente['ID_Cliente']),
                ),
              ],
            ),
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          showClienteDialog(onSave: (data) => createCliente(data));
        },
        child: Icon(Icons.add),
      ),
    );
  }
}
