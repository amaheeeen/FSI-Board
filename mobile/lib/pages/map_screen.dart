import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/tracking_service.dart';

class MapScreen extends ConsumerStatefulWidget {
  const MapScreen({super.key});

  @override
  ConsumerState<MapScreen> createState() => _MapScreenState();
}

class _MapScreenState extends ConsumerState<MapScreen> {
  bool isTracking = false;

  @override
  Widget build(BuildContext context) {
    final trackingService = ref.read(trackingServiceProvider);

    return Scaffold(
      appBar: AppBar(title: const Text("Jamaah Tracking")),
      body: Container(
        color: Colors.grey[200],
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.map, size: 80, color: Colors.grey),
              const SizedBox(height: 16),
              const Text("Google Map Placeholder",
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const Padding(
                  padding: EdgeInsets.all(16.0),
                  child: Text(
                    "Style: Silver/Desaturated\nMarkers: Green (Safe), Red (SOS)",
                    textAlign: TextAlign.center,
                  )),
              const SizedBox(height: 30),

              // Tracking Control
              ElevatedButton.icon(
                onPressed: () {
                  if (isTracking) {
                    trackingService.stopTracking();
                  } else {
                    trackingService.startTracking();
                  }
                  setState(() {
                    isTracking = !isTracking;
                  });
                },
                icon: Icon(isTracking ? Icons.stop : Icons.play_arrow),
                label: Text(isTracking
                    ? "Stop Background Tracking"
                    : "Start Tracking (Every 10m)"),
                style: ElevatedButton.styleFrom(
                  backgroundColor:
                      isTracking ? Colors.red : const Color(0xFF10B981),
                  foregroundColor: Colors.white,
                  padding:
                      const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                ),
              ),

              const SizedBox(height: 30),
              // Simulation of Markers
              const Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.location_on,
                      color: Color(0xFF10B981), size: 40), // Green Pin
                  SizedBox(width: 8),
                  Text("Jamaah (Safe)"),
                  SizedBox(width: 24),
                  Icon(Icons.location_on,
                      color: Color(0xFFF43F5E), size: 40), // Red Pin
                  SizedBox(width: 8),
                  Text("SOS / Lost"),
                ],
              )
            ],
          ),
        ),
      ),
    );
  }
}
