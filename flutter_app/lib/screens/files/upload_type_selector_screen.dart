import 'package:flutter/material.dart';
import '../../routes/app_router.dart';
import '../../widgets/main_scaffold.dart';
import '../../utils/app_localizations.dart';

class UploadTypeSelectorScreen extends StatelessWidget {
  const UploadTypeSelectorScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return MainScaffold(
      title: 'Upload File',
      showDrawer: false,
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                'What type of resource?',
                style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 32),
              
              // General Resource Button
              SizedBox(
                width: double.infinity,
                height: 120,
                child: Card(
                  elevation: 4,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: InkWell(
                    onTap: () {
                      Navigator.of(context).pushNamed(AppRouter.uploadGeneralFile);
                    },
                    borderRadius: BorderRadius.circular(16),
                    child: Padding(
                      padding: const EdgeInsets.all(24),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.description_rounded,
                            size: 40,
                            color: Theme.of(context).primaryColor,
                          ),
                          const SizedBox(height: 12),
                          const Text(
                            'General Resource',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            'Exam, Worksheet, Summary',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.grey[600],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // Lesson Plans Button
              SizedBox(
                width: double.infinity,
                height: 120,
                child: Card(
                  elevation: 4,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: InkWell(
                    onTap: () {
                      Navigator.of(context).pushNamed(AppRouter.uploadPlanFile);
                    },
                    borderRadius: BorderRadius.circular(16),
                    child: Padding(
                      padding: const EdgeInsets.all(24),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.calendar_today_rounded,
                            size: 40,
                            color: Colors.orange,
                          ),
                          const SizedBox(height: 12),
                          const Text(
                            'Lesson Plans',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            'Daily, Weekly, Monthly',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.grey[600],
                            ),
                          ),
                        ],
                      ),
                    ),
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
