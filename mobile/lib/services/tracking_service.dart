import 'dart:async';
import 'package:geolocator/geolocator.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_client.dart';

final trackingServiceProvider = Provider<TrackingService>((ref) {
  return TrackingService(ApiClient());
});

class TrackingService {
  final ApiClient _apiClient;
  Timer? _timer;

  TrackingService(this._apiClient);

  Future<void> startTracking() async {
    // 1. Request Permission
    // LocationPermission permission = await Geolocator.checkPermission();
    // if (permission == LocationPermission.denied) {
    //   permission = await Geolocator.requestPermission();
    //   if (permission == LocationPermission.denied) {
    //     return; // Handle denied
    //   }
    // }
    // Assuming permissions handled or granted for MVP

    // 2. Send initial location
    await _sendLocation();

    // 3. Start Timer (Every 10 minutes)
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(minutes: 10), (timer) async {
      await _sendLocation();
    });

    print("Tracking Started: Sending location every 10 mins");
  }

  Future<void> stopTracking() async {
    _timer?.cancel();
    _timer = null;
    print("Tracking Stopped");
  }

  Future<void> _sendLocation() async {
    try {
      Position position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high);

      // Send POST request
      // We aren't awaiting the response to block execution
      _apiClient.client.post('/api/tracking', data: {
        'latitude': position.latitude,
        'longitude': position.longitude,
        'timestamp': DateTime.now().toIso8601String(),
      }).then((response) {
        print("Location sent: ${position.latitude}, ${position.longitude}");
      }).catchError((e) {
        print("Failed to send location: $e");
      });
    } catch (e) {
      print("Error getting location: $e");
    }
  }
}
