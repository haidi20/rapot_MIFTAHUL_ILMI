import 'package:flutter_modular/flutter_modular.dart';
import 'package:rapot/modules/_main_module.dart';
import 'package:rapot/modules/class_module.dart';
import 'package:rapot/modules/member_module.dart';

class AppModule extends Module {
  // Provide a list of dependencies to inject into your project
  @override
  final List<Bind> binds = [];

  // Provide all the routes for your module
  @override
  final List<ModularRoute> routes = [
    // ChildRoute('/', child: (_, args) => HomeModule()),
    ModuleRoute(
      '/',
      module: MainModule(),
    ),
    ModuleRoute(
      '/class',
      module: ClassModule(),
    ),
    ModuleRoute(
      '/member',
      module: MemberModule(),
    ),
  ];
}
