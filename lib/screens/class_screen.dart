import 'dart:core';
import 'package:flutter/material.dart';

class ClassScreen extends StatefulWidget {
  // ClassScreen({Key key}) : super(key: key);

  @override
  _ClassScreenState createState() => _ClassScreenState();
}

class _ClassScreenState extends State<ClassScreen> {
  @override
  Widget build(BuildContext context) {
    return Stack(
      children: <Widget>[
        Container(
          child: Center(
            child: Text("kelas"),
          ),
        ),
        Positioned(
          bottom: 50,
          right: 20,
          child: FloatingActionButton(
            child: Icon(Icons.add),
            backgroundColor: Colors.green,
            heroTag: 1,
            onPressed: () {
              //do something on press
            },
          ),
        ),
      ],
    );
  }
}
