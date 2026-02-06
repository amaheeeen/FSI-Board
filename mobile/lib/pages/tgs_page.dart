import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/tgs_service.dart';

class TgsPage extends ConsumerStatefulWidget {
  const TgsPage({super.key});

  @override
  ConsumerState<TgsPage> createState() => _TgsPageState();
}

class _TgsPageState extends ConsumerState<TgsPage>
    with SingleTickerProviderStateMixin {
  bool isJoined = false;
  late AnimationController _animationController;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    ref.read(tgsServiceProvider).initialize();

    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 1),
    )..repeat(reverse: true);

    _animation = Tween<double>(begin: 1.0, end: 1.2).animate(
      CurvedAnimation(parent: _animationController, curve: Curves.easeInOut),
    );
  }

  @override
  void dispose() {
    ref.read(tgsServiceProvider).leaveChannel();
    _animationController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final tgsService = ref.watch(tgsServiceProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text("Audio Room"),
        backgroundColor: Colors.white,
        elevation: 0,
        foregroundColor: Colors.black,
      ),
      backgroundColor: Colors.white,
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Status Text
            Text(
              isJoined ? "LIVE REQUEST" : "TAP TO JOIN",
              style: TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                letterSpacing: 1.2,
                color: isJoined ? const Color(0xFFF43F5E) : Colors.grey,
              ),
            ),
            const SizedBox(height: 40),

            // Animated Button
            GestureDetector(
              onTap: () async {
                if (isJoined) {
                  await tgsService.leaveChannel();
                  setState(() => isJoined = false);
                } else {
                  await tgsService.joinChannel(
                      "packet-123", true); // Mocking Role as Mutawwif for Demo
                  setState(() => isJoined = true);
                }
              },
              child: AnimatedBuilder(
                animation: _animation,
                builder: (context, child) {
                  return Transform.scale(
                    scale: isJoined ? _animation.value : 1.0,
                    child: Container(
                      width: 150,
                      height: 150,
                      decoration: BoxDecoration(
                        color: isJoined
                            ? const Color(0xFFF43F5E)
                            : const Color(0xFF10B981),
                        shape: BoxShape.circle,
                        boxShadow: [
                          BoxShadow(
                            color: (isJoined
                                    ? const Color(0xFFF43F5E)
                                    : const Color(0xFF10B981))
                                .withOpacity(0.4),
                            blurRadius: 30,
                            spreadRadius: 10,
                          )
                        ],
                      ),
                      child: Icon(
                        isJoined ? Icons.mic : Icons.headset,
                        color: Colors.white,
                        size: 60,
                      ),
                    ),
                  );
                },
              ),
            ),
            const SizedBox(height: 40),
            Text(
              isJoined ? "Broadcasting Audio..." : "Join as Listener",
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w600,
                color: Colors.black87,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
