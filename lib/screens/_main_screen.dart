import 'package:flutter/material.dart';
import 'package:rapot/helpers.dart';
import 'package:rapot/screens/class_screen.dart';
import 'package:rapot/screens/member_screen.dart';
// import 'package:shared_preferences/shared_preferences.dart';

class MainScreen extends StatefulWidget {
  // MainScreen({Key key}) : super(key: key);

  @override
  _MainScreenState createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  int _currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    Helpers helpers = new Helpers(context);
    // final AuthCubit authCubit = BlocProvider.of<AuthCubit>(context);

    List<Widget> _widgetOoption = <Widget>[
      ClassScreen(),
      MemberScreen(),
    ];

    return Scaffold(
      appBar: AppBar(
          backgroundColor: Color(0xff92A7D6),
          centerTitle: true,
          title: Text("MIFTAHUL 'ILMI SAMARINDA")),
      body: Center(
        child: Container(
          width: helpers.getWidthScreen(),
          height: helpers.height,
          decoration: BoxDecoration(color: Color(0xffFFFFFF)),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: <Widget>[
              Expanded(
                flex: 10,
                child: _widgetOoption.elementAt(_currentIndex),
              ),
              // state ? Text("login") : Text("tidak login"),
              // authBloc.authed ? Text("login") : Text("tidak login"),
              Expanded(
                flex: 1,
                child: Theme(
                  data: Theme.of(context).copyWith(
                    // sets the background color of the `BottomNavigationBar`
                    canvasColor: Colors.white,
                    // sets the active color of the `BottomNavigationBar` if `Brightness` is light
                    primaryColor: Color(0xff92A7D6),
                    textTheme: Theme.of(context).textTheme.copyWith(
                          caption: new TextStyle(color: Colors.yellow),
                        ),
                  ),
                  child: BottomNavigationBar(
                    currentIndex:
                        _currentIndex, // this will be set when a new tab is tapped
                    items: [
                      BottomNavigationBarItem(
                        icon: new Icon(Icons.class_),
                        label: "Kelas",
                      ),
                      BottomNavigationBarItem(
                        icon: new Icon(Icons.person),
                        label: "Member",
                      ),
                    ],
                    onTap: (index) {
                      setState(() {
                        _currentIndex = index;
                      });
                    },
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
