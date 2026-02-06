import 'package:flutter_riverpod/flutter_riverpod.dart';

final tgsServiceProvider = Provider<TgsService>((ref) => TgsService());

class TgsService {
  Future<void> initialize() async {
    // Mock Initialize Agora
    await Future.delayed(const Duration(milliseconds: 500));
  }

  Future<void> joinChannel(String channelName, bool isBroadcaster) async {
    // Mock Join Channel
    await Future.delayed(const Duration(seconds: 1));
    print(
        "Joined channel $channelName as ${isBroadcaster ? 'Broadcaster' : 'Audience'}");
  }

  Future<void> leaveChannel() async {
    // Mock Leave Channel
    await Future.delayed(const Duration(milliseconds: 500));
    print("Left channel");
  }
}
