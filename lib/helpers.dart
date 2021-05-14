import 'package:flutter/material.dart';

class Helpers {
  BuildContext? context;
  double width = 0,
      height = 0,
      widthDesktop = 0,
      widthTablet = 0,
      widthMobile = 0,
      topPadding = 0,
      widthScreen = 0,
      widthLargeDesktop = 0;

  // AuthBloc authBloc = AuthBloc();

  Helpers(BuildContext context) {
    context = context;
    width = MediaQuery.of(context).size.width;
    height = MediaQuery.of(context).size.height;

    widthLargeDesktop = 500;
    widthDesktop = 400;
    widthMobile = width;
    widthTablet = width;
  }

  double getHeight() {
    return height;
  }

  double getWidthScreen() {
    if (width >= 1200) {
      widthScreen = widthLargeDesktop;
    } else if (width >= 992 && width <= 1200) {
      widthScreen = widthDesktop;
    } else if (width >= 768) {
      widthScreen = widthTablet;
    } else if (width <= 600) {
      widthScreen = width;
    }

    return widthScreen;
  }

  double getTopLogin() {
    if (width <= 600) {
      return 50;
    }

    return 150;
  }
}
