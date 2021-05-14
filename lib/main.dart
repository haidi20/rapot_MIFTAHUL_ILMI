import 'package:flutter/material.dart';
import 'package:flutter_modular/flutter_modular.dart';
import 'package:rapot/modules/_app_module.dart';
import 'package:rapot/screens/_app_screen.dart';

void main() {
  runApp(
    ModularApp(
      module: AppModule(),
      child: AppScreen(),
    ),
  );
}
