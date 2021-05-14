import 'package:flutter_modular/flutter_modular.dart';
import 'package:rapot/screens/member_form_screen.dart';

class MemberModule extends Module {
  @override
  final List<Bind> binds = [];

  @override
  final List<ModularRoute> routes = [
    ChildRoute('/form', child: (_, args) => MemberFormScreen()),
  ];
}
