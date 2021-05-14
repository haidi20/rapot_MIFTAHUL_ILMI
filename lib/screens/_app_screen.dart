import 'package:flutter/material.dart';
import 'package:flutter_modular/flutter_modular.dart';

class AppScreen extends StatelessWidget {
  // AuthBloc authBloc = AuthBloc();

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Rapot',
      theme: new ThemeData(
        scaffoldBackgroundColor: Color(0xffF7F7F7),
      ),
      debugShowCheckedModeBanner: false,
      initialRoute: '/',
    ).modular();
  }
}
