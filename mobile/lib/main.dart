import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';

import 'pages/login_page.dart';
import 'pages/home_page.dart';
import 'pages/tgs_page.dart';
import 'pages/map_screen.dart'; // Ensure map_screen.dart exists or remove this if not ready

void main() {
  runApp(const ProviderScope(child: MyApp()));
}

final _router = GoRouter(
  initialLocation:
      '/login', // Start at login for now, auth check logic can be added
  routes: [
    GoRoute(
      path: '/login',
      builder: (context, state) => const LoginPage(),
    ),
    GoRoute(
      path: '/home',
      builder: (context, state) => const HomePage(),
    ),
    GoRoute(
      path: '/tgs',
      builder: (context, state) => const TgsPage(),
    ),
    GoRoute(
      path: '/tracking',
      builder: (context, state) =>
          const MapScreen(), // Ensure map_screen.dart is implemented
    ),
  ],
);

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'FSI Travel',
      routerConfig: _router,
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF10B981), // Emerald
          primary: const Color(0xFF10B981),
          secondary: const Color(0xFFF43F5E), // Rose / Red
        ),
        textTheme: GoogleFonts.interTextTheme(),
      ),
    );
  }
}
