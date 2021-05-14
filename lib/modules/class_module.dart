// import 'package:flutter/src/widgets/framework.dart';
import 'package:flutter_modular/flutter_modular.dart';
import 'package:rapot/screens/class_form_screen.dart';

class ClassModule extends Module {
  @override
  final List<Bind> binds = [];

  @override
  final List<ModularRoute> routes = [
    ChildRoute('/form', child: (_, args) => ClassFormScreen()),
  ];
}
