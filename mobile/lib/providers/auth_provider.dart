import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:dio/dio.dart';
import '../services/api_client.dart';

final apiClientProvider = Provider<ApiClient>((ref) => ApiClient());

final authProvider =
    StateNotifierProvider<AuthNotifier, AsyncValue<void>>((ref) {
  return AuthNotifier(ref.watch(apiClientProvider));
});

class AuthNotifier extends StateNotifier<AsyncValue<void>> {
  final ApiClient _apiClient;

  AuthNotifier(this._apiClient) : super(const AsyncValue.data(null));

  Future<bool> login(String email, String password) async {
    state = const AsyncValue.loading();
    try {
      final response = await _apiClient.client.post('/api/login', data: {
        'email': email,
        'password': password,
      });

      if (response.statusCode == 200) {
        final token =
            response.data['token']; // Adjust based on actual API response
        await _apiClient.setToken(token);
        state = const AsyncValue.data(null);
        return true;
      } else {
        state = AsyncValue.error('Login failed', StackTrace.current);
        return false;
      }
    } on DioException catch (e) {
      state = AsyncValue.error(
          e.response?.data['message'] ?? 'Connection error',
          StackTrace.current);
      return false;
    } catch (e) {
      state = AsyncValue.error(e.toString(), StackTrace.current);
      return false;
    }
  }

  Future<void> logout() async {
    await _apiClient.clearToken();
  }
}
